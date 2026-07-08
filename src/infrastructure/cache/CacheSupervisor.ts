import { CachePort, Product, Category } from "../../types";

export interface CacheMetrics {
  hits: number;
  misses: number;
  evictions: number;
  memoryUsage: number;
}

export interface CacheEntry<T> {
  value: T;
  expiry: number;
  accessCount: number;
  lastAccess: number;
}

export class CacheSupervisor implements CachePort {
  private cache: Map<string, CacheEntry<any>> = new Map();
  private metrics: CacheMetrics = { hits: 0, misses: 0, evictions: 0, memoryUsage: 0 };
  private readonly maxSize: number;
  private readonly defaultTtl: number;
  private cleanupTimer: NodeJS.Timeout | null = null;

  constructor(maxSize: number = 1000, defaultTtl: number = 300000) {
    this.maxSize = maxSize;
    this.defaultTtl = defaultTtl;
    this.startCleanupTimer();
  }

  async get<T>(key: string): Promise<T | null> {
    const entry = this.cache.get(key);
    
    if (!entry) {
      this.metrics.misses++;
      return null;
    }

    if (Date.now() > entry.expiry) {
      this.cache.delete(key);
      this.metrics.misses++;
      this.metrics.evictions++;
      return null;
    }

    entry.accessCount++;
    entry.lastAccess = Date.now();
    this.metrics.hits++;
    return entry.value as T;
  }

  async set<T>(key: string, value: T, ttl?: number): Promise<void> {
    if (this.cache.size >= this.maxSize) {
      this.evictLeastUsed();
    }

    const entry: CacheEntry<T> = {
      value,
      expiry: Date.now() + (ttl || this.defaultTtl),
      accessCount: 0,
      lastAccess: Date.now()
    };

    this.cache.set(key, entry);
    this.updateMemoryUsage();
  }

  async delete(key: string): Promise<void> {
    this.cache.delete(key);
    this.updateMemoryUsage();
  }

  async clear(): Promise<void> {
    this.cache.clear();
    this.metrics = { hits: 0, misses: 0, evictions: 0, memoryUsage: 0 };
  }

  async getProducts(): Promise<Product[]> {
    return this.get<Product[]>('products:all') || [];
  }

  async setProducts(products: Product[]): Promise<void> {
    await this.set('products:all', products, 600000); // 10 minutes
  }

  async getProduct(id: string): Promise<Product | null> {
    return this.get<Product>(`products:${id}`);
  }

  async setProduct(id: string, product: Product): Promise<void> {
    await this.set(`products:${id}`, product, 600000);
  }

  async getCategories(): Promise<Category[]> {
    return this.get<Category[]>('categories:all') || [];
  }

  async setCategories(categories: Category[]): Promise<void> {
    await this.set('categories:all', categories, 900000); // 15 minutes
  }

  async invalidateProduct(id: string): Promise<void> {
    await this.delete(`products:${id}`);
    await this.delete('products:all');
    await this.delete('products:featured');
    await this.delete('products:search');
  }

  async invalidateCategory(id: string): Promise<void> {
    await this.delete(`categories:${id}`);
    await this.delete('categories:all');
  }

  async getCacheStats(): Promise<{ size: number; hitRate: number }> {
    const total = this.metrics.hits + this.metrics.misses;
    return {
      size: this.cache.size,
      hitRate: total > 0 ? this.metrics.hits / total : 0
    };
  }

  getMetrics(): CacheMetrics {
    return { ...this.metrics };
  }

  private evictLeastUsed(): void {
    let leastUsedKey: string | null = null;
    let leastUsedCount = Infinity;

    for (const [key, entry] of this.cache.entries()) {
      if (entry.accessCount < leastUsedCount) {
        leastUsedCount = entry.accessCount;
        leastUsedKey = key;
      }
    }

    if (leastUsedKey) {
      this.cache.delete(leastUsedKey);
      this.metrics.evictions++;
    }
  }

  private updateMemoryUsage(): void {
    this.metrics.memoryUsage = this.cache.size * 1024; // Rough estimate
  }

  private startCleanupTimer(): void {
    this.cleanupTimer = setInterval(() => {
      this.cleanupExpired();
    }, 60000); // Every minute
  }

  private cleanupExpired(): void {
    const now = Date.now();
    for (const [key, entry] of this.cache.entries()) {
      if (now > entry.expiry) {
        this.cache.delete(key);
        this.metrics.evictions++;
      }
    }
  }

  destroy(): void {
    if (this.cleanupTimer) {
      clearInterval(this.cleanupTimer);
    }
  }
}

export const cacheSupervisor = new CacheSupervisor();