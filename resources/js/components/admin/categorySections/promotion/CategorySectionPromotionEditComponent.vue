<template>
    <LoadingComponent :props="loading" />

    <div id="categorySectionPromotionEditModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Editar Banner</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="close"></button>
            </div>
            <div class="modal-body">
                <div class="form-row" v-if="message">
                    <div class="form-col-12 db-field-alert">{{ message }}</div>
                </div>
                <form @submit.prevent="save" class="d-block w-full">
                    <div class="form-row">

                        <!-- Nome -->
                        <div class="form-col-12">
                            <label for="edit_promo_name" class="db-field-title required">{{ $t("label.name") }}</label>
                            <input v-model="form.name" v-bind:class="errors.name ? 'invalid' : ''"
                                type="text" id="edit_promo_name" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.name">{{ errors.name[0] }}</small>
                        </div>

                        <!-- Status -->
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required">{{ $t("label.status") }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="form.status" id="ep_active"
                                            :value="enums.statusEnum.ACTIVE" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="ep_active" class="db-field-label">{{ $t("label.active") }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="form.status" id="ep_inactive"
                                            :value="enums.statusEnum.INACTIVE" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="ep_inactive" class="db-field-label">{{ $t("label.inactive") }}</label>
                                </div>
                            </div>
                        </div>

                        <!-- Tipo -->
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required">{{ $t("label.type") }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="form.type" id="ep_small"
                                            :value="enums.promotionTypeEnum.SMALL" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="ep_small" class="db-field-label">{{ $t("label.small") }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="form.type" id="ep_big"
                                            :value="enums.promotionTypeEnum.BIG" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="ep_big" class="db-field-label">{{ $t("label.big") }}</label>
                                </div>
                            </div>
                        </div>

                        <!-- Tipo de Link -->
                        <div class="form-col-12">
                            <label class="db-field-title">Tipo de Link da Imagem</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="form.link_type" id="ep_link_none"
                                            :value="null" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="ep_link_none" class="db-field-label">Nenhum</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="form.link_type" id="ep_link_category"
                                            value="category" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="ep_link_category" class="db-field-label">Categoria</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input type="radio" v-model="form.link_type" id="ep_link_custom"
                                            value="custom" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="ep_link_custom" class="db-field-label">Link personalizado</label>
                                </div>
                            </div>
                        </div>

                        <!-- Categoria -->
                        <div class="form-col-12" v-if="form.link_type === 'category'">
                            <label class="db-field-title">Categoria</label>
                            <vue-select class="db-field-control f-b-custom-select"
                                v-bind:class="errors.link_url ? 'invalid' : ''"
                                v-model="form.link_url"
                                :options="categories" label-by="name" value-by="slug"
                                :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                placeholder="Selecione a categoria..." search-placeholder="Buscar..." />
                            <small class="db-field-alert" v-if="errors.link_url">{{ errors.link_url[0] }}</small>
                        </div>

                        <!-- URL personalizada -->
                        <div class="form-col-12" v-if="form.link_type === 'custom'">
                            <label for="ep_link_url" class="db-field-title">URL do Link</label>
                            <input v-model="form.link_url" v-bind:class="errors.link_url ? 'invalid' : ''"
                                type="text" id="ep_link_url" class="db-field-control"
                                placeholder="https://..." />
                            <small class="db-field-alert" v-if="errors.link_url">{{ errors.link_url[0] }}</small>
                        </div>

                        <!-- Imagem atual -->
                        <div class="form-col-12" v-if="currentCover && !imagePreview">
                            <label class="db-field-title">Imagem Atual</label>
                            <img :src="currentCover" class="h-20 rounded-lg object-cover" alt="banner" />
                        </div>

                        <!-- Nova Imagem -->
                        <div class="form-col-12">
                            <label class="db-field-title">Nova Imagem (opcional — 540px, 336px)</label>
                            <input @change="changeImage" v-bind:class="errors.image ? 'invalid' : ''"
                                id="ep_image" type="file" class="db-field-control"
                                ref="imageInput" accept="image/png, image/jpeg, image/jpg" />
                            <small class="db-field-alert" v-if="errors.image">{{ errors.image[0] }}</small>
                        </div>

                        <!-- Preview da nova imagem -->
                        <div class="form-col-12" v-if="imagePreview">
                            <label class="db-field-title">Preview</label>
                            <img :src="imagePreview" class="h-20 rounded-lg object-cover" alt="preview" />
                        </div>

                        <!-- Botões -->
                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click="close">
                                    <i class="lab lab-fill-close-circle"></i>
                                    <span>{{ $t("button.close") }}</span>
                                </button>
                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-fill-save"></i>
                                    <span>{{ $t("button.save") }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import statusEnum from "../../../../enums/modules/statusEnum";
import promotionTypeEnum from "../../../../enums/modules/promotionTypeEnum";

export default {
    name: "CategorySectionPromotionEditComponent",
    components: { LoadingComponent },
    props: {
        sectionId: { type: Number },
        sectionSearch: { type: Object },
    },
    data() {
        return {
            loading: { isActive: false },
            enums: { statusEnum, promotionTypeEnum },
            form: {
                name: "",
                status: statusEnum.ACTIVE,
                type: promotionTypeEnum.SMALL,
                link_type: null,
                link_url: null,
            },
            image: null,
            imagePreview: null,
            currentCover: null,
            errors: {},
            message: null,
        };
    },
    computed: {
        categories() {
            return this.$store.getters['productCategory/lists'];
        },
    },
    mounted() {
        this.$store.dispatch('productCategory/lists', {
            paginate: 0,
            order_column: 'name',
            order_type: 'asc',
            status: statusEnum.ACTIVE,
        });
    },
    methods: {
        open(item) {
            this.form = {
                name: item.promotion_name || "",
                status: item.promotion_status ?? statusEnum.ACTIVE,
                type: item.promotion_type ?? promotionTypeEnum.SMALL,
                link_type: item.promotion_link_type || null,
                link_url: item.promotion_link_url || null,
            };
            this.currentCover = item.promotion_cover || null;
            this.image = null;
            this.imagePreview = null;
            this.errors = {};
            this.message = null;
            if (this.$refs.imageInput) this.$refs.imageInput.value = null;

            this.$store.dispatch("promotion/edit", item.promotion_id);
            appService.modalShow('#categorySectionPromotionEditModal');
        },
        close() {
            appService.modalHide('#categorySectionPromotionEditModal');
            this.$store.dispatch("promotion/reset").then().catch();
            this.errors = {};
            this.message = null;
        },
        changeImage(e) {
            this.image = e.target.files[0] || null;
            this.imagePreview = this.image ? URL.createObjectURL(this.image) : null;
        },
        save() {
            try {
                const fd = new FormData();
                fd.append("name", this.form.name);
                fd.append("type", this.form.type);
                fd.append("status", this.form.status);
                if (this.form.link_type) {
                    fd.append("link_type", this.form.link_type);
                    if (this.form.link_url) fd.append("link_url", this.form.link_url);
                }
                if (this.image) fd.append("image", this.image);

                this.loading.isActive = true;
                this.$store.dispatch("promotion/save", {
                    form: fd,
                    search: { paginate: 0 },
                }).then(() => {
                    this.$store.dispatch("categorySectionPromotion/lists", {
                        ...this.sectionSearch,
                        id: this.sectionId,
                    }).then(() => {
                        this.loading.isActive = false;
                        appService.modalHide('#categorySectionPromotionEditModal');
                        alertService.successFlip(1, "Banner");
                    }).catch(() => {
                        this.loading.isActive = false;
                        appService.modalHide('#categorySectionPromotionEditModal');
                        alertService.successFlip(1, "Banner");
                    });
                }).catch((err) => {
                    this.loading.isActive = false;
                    if (err.response?.data?.errors) {
                        this.errors = err.response.data.errors;
                    } else {
                        this.message = err.response?.data?.message ?? null;
                    }
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
};
</script>
