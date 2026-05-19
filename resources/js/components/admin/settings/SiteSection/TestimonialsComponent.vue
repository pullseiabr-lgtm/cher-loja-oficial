<template>
    <div id="testimonials" class="db-card db-tab-div active">
        <div class="db-card-header">
            <h3 class="db-card-title">Seções de Depoimentos</h3>
        </div>
        <div class="db-card-body">
            <div class="testimonials-coming-soon">
                <div class="testimonials-icon">
                    <i class="lab lab-line-review"></i>
                </div>
                <h4>Em breve</h4>
                <p>A funcionalidade de depoimentos está sendo desenvolvida e estará disponível em breve.</p>
                <router-link :to="{ name: 'admin.settings.siteSection' }" class="db-btn text-white bg-primary">
                    <i class="fa-solid fa-arrow-left mr-1"></i>
                    Voltar
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "TestimonialsComponent",
};
</script>

<style scoped>
.testimonials-coming-soon {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
    text-align: center;
    gap: 0.75rem;
}

.testimonials-icon {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: #fce4ec;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #ad1457;
    margin-bottom: 0.5rem;
}

.testimonials-coming-soon h4 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.testimonials-coming-soon p {
    font-size: 0.875rem;
    color: #6b7280;
    max-width: 360px;
    margin: 0;
    line-height: 1.5;
}
</style>
