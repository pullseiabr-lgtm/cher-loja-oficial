<template>
    <div class="db-content-area">
        <BreadcrumbComponent :props="{ page: $t('menu.site_menus'), pageRoute: 'admin.site-menus', subPage: breadcrumb }" />
        <router-view />
    </div>
</template>

<script>
import BreadcrumbComponent from "../components/BreadcrumbComponent";
export default {
    name: "SiteMenuComponent",
    components: { BreadcrumbComponent },
    computed: {
        breadcrumb() {
            return this.$route.meta?.breadcrumb || '';
        }
    }
}
</script>
