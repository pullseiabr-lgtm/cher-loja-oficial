<template>
    <LoadingComponent :props="loading" />

    <footer class="pt-10 mobile:hidden" style="background-color: #00b8a9;">
        <div class="container">
            <div class="flex flex-wrap gap-10 lg:gap-6 justify-between pb-10">

                <!-- Logo + Redes Sociais -->
                <div class="flex-shrink-0">
                    <router-link :to="{ name: 'frontend.home' }">
                        <img class="mb-6 w-36" :src="setting.theme_footer_logo" alt="logo">
                    </router-link>
                    <div class="flex items-center gap-3 mt-2">
                        <a v-if="setting.social_media_instagram" target="_blank"
                            :href="setting.social_media_instagram"
                            class="w-9 h-9 flex items-center justify-center rounded text-white border border-white/40 hover:bg-white/20 transition-all duration-300">
                            <i class="lab-fill-instagram text-base"></i>
                        </a>
                        <a v-if="setting.company_email" :href="'mailto:' + setting.company_email"
                            class="w-9 h-9 flex items-center justify-center rounded text-white border border-white/40 hover:bg-white/20 transition-all duration-300">
                            <i class="lab-fill-mail text-base"></i>
                        </a>
                        <a v-if="setting.company_phone"
                            :href="'https://wa.me/' + setting.company_phone.replace(/\D/g, '')" target="_blank"
                            class="w-9 h-9 flex items-center justify-center rounded text-white border border-white/40 hover:bg-white/20 transition-all duration-300">
                            <i class="lab-fill-whatsapp text-base"></i>
                        </a>
                    </div>
                </div>

                <!-- Footer Menu Sections (dynamic from DB) -->
                <template v-if="footerMenuGroups.length > 0">
                    <div v-for="group in footerMenuGroups" :key="group.id" class="min-w-[140px]">
                        <h4 class="text-sm font-bold uppercase tracking-widest mb-5 text-white">{{ group.title }}</h4>
                        <nav class="flex flex-col gap-3">
                            <template v-for="child in group.children" :key="child.id">
                                <router-link v-if="child.status === 1 && child.type === 'page' && child.url"
                                    class="text-sm text-white/90 hover:text-white transition-all duration-300"
                                    :to="{ name: 'frontend.page', params: { slug: child.url } }">
                                    {{ child.title }}
                                </router-link>
                                <router-link v-else-if="child.status === 1 && child.type === 'category' && child.url"
                                    class="text-sm text-white/90 hover:text-white transition-all duration-300"
                                    :to="{ name: 'frontend.product', query: { category: child.url } }">
                                    {{ child.title }}
                                </router-link>
                                <router-link v-else-if="child.status === 1 && child.type === 'custom' && child.url && isInternalUrl(child.url)"
                                    class="text-sm text-white/90 hover:text-white transition-all duration-300"
                                    :to="child.url">
                                    {{ child.title }}
                                </router-link>
                                <a v-else-if="child.status === 1 && child.type === 'custom' && child.url"
                                    :href="child.url" :target="child.target"
                                    class="text-sm text-white/90 hover:text-white transition-all duration-300">
                                    {{ child.title }}
                                </a>
                            </template>
                        </nav>
                    </div>
                </template>

                <!-- Fallback to old page sections if no footer menu configured -->
                <template v-else>
                    <div class="min-w-[140px]">
                        <h4 class="text-sm font-bold uppercase tracking-widest mb-5 text-white">EMPRESA</h4>
                        <nav class="flex flex-col gap-3">
                            <router-link v-for="page in supportPages" :key="page.slug"
                                class="text-sm text-white/90 hover:text-white transition-all duration-300"
                                :to="{ name: 'frontend.page', params: { slug: page.slug } }">
                                {{ page.title }}
                            </router-link>
                        </nav>
                    </div>
                    <div class="min-w-[180px]">
                        <h4 class="text-sm font-bold uppercase tracking-widest mb-5 text-white">AJUDA</h4>
                        <nav class="flex flex-col gap-3">
                            <router-link v-for="page in legalPages" :key="page.slug"
                                class="text-sm text-white/90 hover:text-white transition-all duration-300"
                                :to="{ name: 'frontend.page', params: { slug: page.slug } }">
                                {{ page.title }}
                            </router-link>
                        </nav>
                    </div>
                </template>

                <!-- ATENDIMENTO + PAGAMENTO + SELOS -->
                <div class="min-w-[190px] flex flex-col gap-6">

                    <!-- Atendimento -->
                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-widest mb-4 text-white">ATENDIMENTO</h4>
                        <div class="flex flex-col gap-3">
                            <a v-if="setting.company_phone"
                                :href="'https://wa.me/' + setting.company_phone.replace(/\D/g, '')" target="_blank"
                                class="flex items-center gap-2 text-sm text-white/90 hover:text-white transition-all duration-300">
                                <i class="lab-fill-whatsapp text-lg"></i>
                                <span>Whatsapp</span>
                            </a>
                            <a v-if="setting.company_email" :href="'mailto:' + setting.company_email"
                                class="flex items-center gap-2 text-sm text-white/90 hover:text-white transition-all duration-300">
                                <i class="lab-fill-mail text-lg"></i>
                                <span>E-mail</span>
                            </a>
                        </div>
                    </div>

                    <!-- Formas de Pagamento -->
                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-widest mb-4 text-white">FORMAS DE PAGAMENTO</h4>
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center gap-2 text-sm text-white/90">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-5 h-5 fill-white flex-shrink-0" viewBox="0 0 24 24">
                                        <path d="M12.001 2C6.476 2 2 6.476 2 12.001S6.476 22 12.001 22 22 17.525 22 12.001 17.525 2 12.001 2zm-.5 3.5h1v1.25c1.38.25 2.5 1.24 2.5 2.56 0 .28-.23.5-.5.5-.28 0-.5-.22-.5-.5 0-.82-.72-1.56-1.5-1.56-.83 0-1.5.68-1.5 1.5 0 .83.67 1.5 1.5 1.5 1.38 0 2.5 1.12 2.5 2.5 0 1.32-1.12 2.31-2.5 2.56V15.5h-1v-1.24c-1.38-.25-2.5-1.24-2.5-2.56 0-.28.22-.5.5-.5s.5.22.5.5c0 .82.68 1.56 1.5 1.56.82 0 1.5-.68 1.5-1.5 0-.83-.68-1.5-1.5-1.5-1.38 0-2.5-1.12-2.5-2.5 0-1.32 1.12-2.31 2.5-2.56V5.5z"/>
                                    </svg>
                                    <span>Mercado Pago</span>
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-white/90">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-5 h-5 fill-white flex-shrink-0" viewBox="0 0 512 512">
                                        <path d="M242.4 292.5C247.8 287.1 255.1 284.1 262.5 284.1C269.9 284.1 277.2 287.1 282.6 292.5L395.4 405.1C406.3 416 406.3 433.6 395.4 444.5C384.5 455.4 366.9 455.4 356 444.5L262.5 351L168.1 444.5C157.2 455.4 139.6 455.4 128.7 444.5C117.8 433.6 117.8 416 128.7 405.1L242.4 292.5zM242.4 219.5L128.7 106.9C117.8 96 117.8 78.37 128.7 67.5C139.6 56.63 157.2 56.63 168.1 67.5L262.5 161L356 67.5C366.9 56.63 384.5 56.63 395.4 67.5C406.3 78.37 406.3 96 395.4 106.9L282.6 219.5C277.2 224.9 269.9 227.9 262.5 227.9C255.1 227.9 247.8 224.9 242.4 219.5z"/>
                                    </svg>
                                    <span>Pix</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Selos de Segurança -->
                    <div>
                        <h4 class="text-sm font-bold uppercase tracking-widest mb-4 text-white">SELO DE SEGURANÇA</h4>
                        <div class="flex flex-col gap-2">
                            <!-- Google Site Seguro -->
                            <div class="flex items-center gap-2 bg-white rounded-lg px-3 py-2 w-fit">
                                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 48 48">
                                    <path fill="#4caf50" d="M24 4C13 4 4 13 4 24s9 20 20 20 20-9 20-20S35 4 24 4z"/>
                                    <path fill="#fff" d="M21 14l-7 7 2.8 2.8L21 19.6l12.2 12.2L36 29z"/>
                                </svg>
                                <div class="flex flex-col leading-tight">
                                    <span class="text-[9px] font-bold text-gray-700 uppercase tracking-wide">Google</span>
                                    <span class="text-[9px] text-gray-500">Site Seguro</span>
                                </div>
                            </div>
                            <!-- Compra Segura -->
                            <div class="flex items-center gap-2 bg-white rounded-lg px-3 py-2 w-fit">
                                <svg class="w-5 h-5 flex-shrink-0 text-orange-500" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                                </svg>
                                <div class="flex flex-col leading-tight">
                                    <span class="text-[9px] font-bold text-gray-700 uppercase tracking-wide">COMPRA</span>
                                    <span class="text-[9px] font-bold text-orange-500 uppercase tracking-wide">SEGURA</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Copyright -->
        <div class="py-4 mt-2 text-center border-t border-white/10">
            <p class="text-xs font-medium text-white/70">{{ setting.site_copyright }}</p>
        </div>
    </footer>
</template>


<script>
import statusEnum from "../../../enums/modules/statusEnum";
import axios from "axios";
import alertService from "../../../services/alertService";
import LoadingComponent from "../../frontend/components/LoadingComponent";
import menuSectionEnum from "../../../enums/modules/menuSectionEnum";
import _ from "lodash";

export default {
    name: "FrontendFooterComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            legalPages: [],
            supportPages: [],
            enums: {
                statusEnum: statusEnum,
                menuSectionEnum: menuSectionEnum
            },
            errors: {},
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        footerMenuRaw: function () {
            return this.$store.getters['frontendSiteMenu/footer'] || [];
        },
        footerMenuGroups: function () {
            // top-level items with children become section groups
            const topLevel = this.footerMenuRaw.filter(i => !i.parent_id && i.status === 1);
            return topLevel.map(group => ({
                ...group,
                children: this.footerMenuRaw.filter(i => i.parent_id === group.id && i.status === 1),
            })).filter(g => g.children.length > 0);
        },
    },
    mounted() {
        // Load footer menu from DB
        this.$store.dispatch('frontendSiteMenu/footer').then().catch();

        // Also load pages for fallback
        this.loading.isActive = true;
        this.$store.dispatch("frontendPage/lists", {
            paginate: 0,
            order_column: "id",
            order_type: "asc",
            status: this.enums.statusEnum.ACTIVE
        }).then(res => {
            if (res.data.data.length > 0) {
                _.forEach(res.data.data, (page) => {
                    if (page.menu_section_id === this.enums.menuSectionEnum.LEGAL) {
                        this.legalPages.push(page);
                    } else {
                        this.supportPages.push(page);
                    }
                });
            }
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    methods: {
        isInternalUrl(url) {
            if (!url) return false;
            return url.startsWith('/') && !url.startsWith('//');
        },
    },
}
</script>
