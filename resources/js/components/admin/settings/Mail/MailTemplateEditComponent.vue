<template>
    <LoadingComponent :props="loading" />

    <div class="db-card mt-4">
        <div class="db-card-header flex items-center justify-between gap-4">
            <h3 class="db-card-title">{{ template.name }}</h3>
            <button @click="$emit('back')" type="button" class="db-btn-outline h-8 text-xs">
                <i class="lab lab-line-arrow-left text-sm"></i>
                <span>{{ $t("button.back") }}</span>
            </button>
        </div>
        <div class="db-card-body">
            <form @submit.prevent="save" class="w-full">
                <div class="form-row">
                    <div class="form-col-12">
                        <label class="db-field-title required">{{ $t("label.subject") }}</label>
                        <input
                            v-model="form.subject"
                            type="text"
                            class="db-field-control"
                            :class="errors.subject ? 'invalid' : ''"
                            required
                        />
                        <small class="db-field-alert" v-if="errors.subject">{{ errors.subject[0] }}</small>
                    </div>

                    <div class="form-col-12">
                        <label class="db-field-title required">{{ $t("label.body") }}</label>

                        <div v-if="Object.keys(template.variables || {}).length > 0" class="mb-3 flex flex-wrap gap-2">
                            <span class="text-xs text-gray-500 self-center">{{ $t("label.insert_variable") }}:</span>
                            <button
                                v-for="(desc, key) in (template.variables || {})"
                                :key="key"
                                type="button"
                                @click="insertVariable(key)"
                                class="inline-flex items-center gap-1 bg-gray-100 hover:bg-[#00b398]/10 hover:text-[#00b398] border border-gray-200 text-gray-600 text-xs rounded px-2 py-1 font-mono transition"
                                :title="desc"
                            >
                                <i class="lab lab-line-plus text-xs"></i>
                                <span v-text="'{' + '{' + key + '}' + '}'"></span>
                                <span class="text-gray-400 font-sans normal-case ml-1">{{ desc }}</span>
                            </button>
                        </div>

                        <quill-editor
                            v-model:value="form.body"
                            id="email_template_body"
                            :class="errors.body ? 'invalid' : ''"
                            class="!h-64 textarea-border-radius ql-container ql-snow"
                            ref="quillRef"
                        />
                        <small class="db-field-alert" v-if="errors.body">{{ errors.body[0] }}</small>
                    </div>

                    <div class="form-col-12 mt-12">
                        <button type="submit" class="db-btn text-white bg-primary">
                            <i class="lab lab-fill-save"></i>
                            <span>{{ $t("button.save") }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
import alertService from "../../../../services/alertService";
import { quillEditor } from "vue3-quill";

export default {
    name: "MailTemplateEditComponent",
    components: { LoadingComponent, quillEditor },
    props: {
        template: {
            type: Object,
            required: true,
        },
    },
    emits: ["back", "saved"],
    data() {
        return {
            loading: { isActive: false },
            form: {
                id: this.template.id,
                subject: this.template.subject,
                body: this.template.body,
            },
            errors: {},
        };
    },
    watch: {
        template(val) {
            this.form = { id: val.id, subject: val.subject, body: val.body };
            this.errors = {};
        },
    },
    methods: {
        insertVariable(key) {
            const tag = `{{${key}}}`;
            this.form.body = (this.form.body || "") + tag;
        },
        save() {
            this.loading.isActive = true;
            this.$store.dispatch("emailTemplate/save", this.form)
                .then(() => {
                    this.loading.isActive = false;
                    alertService.successFlip(1, this.$t("menu.email_templates"));
                    this.errors = {};
                    this.$store.dispatch("emailTemplate/lists");
                    this.$emit("saved");
                })
                .catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err?.response?.data?.errors ?? {};
                });
        },
    },
};
</script>
