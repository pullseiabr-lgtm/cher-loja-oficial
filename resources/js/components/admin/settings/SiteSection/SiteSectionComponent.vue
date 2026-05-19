<template>
    <div id="site-sections" class="db-card db-tab-div active">
        <div class="db-card-header">
            <h3 class="db-card-title">Seções do Site</h3>
        </div>
        <div class="db-card-body">
            <p class="text-sm text-gray-500 mb-6">
                Gerencie as seções exibidas na loja. Clique em um item para configurar.
            </p>
            <div class="site-section-grid">

                <!-- Seções de Produtos -->
                <router-link :to="{ name: 'admin.product-sections' }" class="site-section-card">
                    <div class="site-section-icon bg-[#e8f5e9]">
                        <i class="lab lab-line-item-section text-[#2e7d32]"></i>
                    </div>
                    <div class="site-section-info">
                        <h4 class="site-section-title">Seções de Produtos</h4>
                        <p class="site-section-desc">Configure blocos de produtos exibidos na página inicial da loja.</p>
                    </div>
                    <div class="site-section-arrow">
                        <i class="fa-solid fa-chevron-right text-gray-400"></i>
                    </div>
                </router-link>

                <!-- Seções de Categorias -->
                <router-link :to="{ name: 'admin.settings.productCategory' }" class="site-section-card">
                    <div class="site-section-icon bg-[#e3f2fd]">
                        <i class="lab lab-line-item-categories text-[#1565c0]"></i>
                    </div>
                    <div class="site-section-info">
                        <h4 class="site-section-title">Seções de Categorias</h4>
                        <p class="site-section-desc">Organize e configure as categorias de produtos visíveis na loja.</p>
                    </div>
                    <div class="site-section-arrow">
                        <i class="fa-solid fa-chevron-right text-gray-400"></i>
                    </div>
                </router-link>

                <!-- Seções de Depoimentos -->
                <router-link :to="{ name: 'admin.settings.siteSection.testimonials' }" class="site-section-card">
                    <div class="site-section-icon bg-[#fce4ec]">
                        <i class="lab lab-line-review text-[#ad1457]"></i>
                    </div>
                    <div class="site-section-info">
                        <h4 class="site-section-title">Seções de Depoimentos</h4>
                        <p class="site-section-desc">Adicione e gerencie depoimentos de clientes exibidos na loja.</p>
                    </div>
                    <div class="site-section-arrow">
                        <i class="fa-solid fa-chevron-right text-gray-400"></i>
                    </div>
                </router-link>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "SiteSectionComponent",
};
</script>

<style scoped>
.site-section-grid {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.site-section-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.1rem 1.25rem;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    background: #fff;
    text-decoration: none;
    color: inherit;
    transition: box-shadow 0.18s, border-color 0.18s, transform 0.15s;
    cursor: pointer;
}

.site-section-card:hover {
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
    border-color: #00b8a9;
    transform: translateY(-1px);
}

.site-section-icon {
    flex-shrink: 0;
    width: 52px;
    height: 52px;
    border-radius: 0.625rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.site-section-info {
    flex: 1;
    min-width: 0;
}

.site-section-title {
    font-size: 0.9375rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.2rem;
    line-height: 1.3;
}

.site-section-desc {
    font-size: 0.8125rem;
    color: #6b7280;
    margin: 0;
    line-height: 1.45;
}

.site-section-arrow {
    flex-shrink: 0;
    font-size: 0.75rem;
    opacity: 0.6;
    transition: opacity 0.15s, transform 0.15s;
}

.site-section-card:hover .site-section-arrow {
    opacity: 1;
    transform: translateX(3px);
}
</style>
