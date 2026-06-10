<template>
    <LoadingComponent :props="loading" />

    <div class="db-card mt-4">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ $t("menu.email_templates") }}</h3>
        </div>
        <div class="db-card-body">
            <div class="db-table-responsive">
                <table class="db-table">
                    <thead class="db-table-head">
                        <tr class="db-table-head-tr">
                            <th class="db-table-head-th">{{ $t("label.name") }}</th>
                            <th class="db-table-head-th">{{ $t("label.subject") }}</th>
                            <th class="db-table-head-th">{{ $t("label.variables") }}</th>
                            <th class="db-table-head-th hidden-print">{{ $t("label.action") }}</th>
                        </tr>
                    </thead>
                    <tbody class="db-table-body">
                        <tr v-if="templates.length === 0">
                            <td colspan="4" class="db-table-body-td text-center">{{ $t("message.no_records") }}</td>
                        </tr>
                        <tr class="db-table-body-tr" v-for="template in templates" :key="template.id">
                            <td class="db-table-body-td font-medium">{{ template.name }}</td>
                            <td class="db-table-body-td text-gray-500">{{ template.subject }}</td>
                            <td class="db-table-body-td">
                                <span
                                    v-for="(desc, key) in (template.variables || {})"
                                    :key="key"
                                    v-text="'{' + '{' + key + '}' + '}'"
                                    class="inline-block bg-gray-100 text-gray-600 text-xs rounded px-2 py-0.5 mr-1 mb-1 font-mono"
                                ></span>
                            </td>
                            <td class="db-table-body-td hidden-print">
                                <button
                                    @click="$emit('edit', template)"
                                    type="button"
                                    class="db-btn-outline h-[30px] text-xs"
                                >
                                    <i class="lab lab-line-edit text-sm"></i>
                                    <span>{{ $t("button.edit") }}</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
import alertService from "../../../../services/alertService";

export default {
    name: "MailTemplateListComponent",
    components: { LoadingComponent },
    emits: ["edit"],
    data() {
        return {
            loading: { isActive: false },
        };
    },
    computed: {
        templates() {
            return this.$store.getters["emailTemplate/lists"];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("emailTemplate/lists")
            .then(() => { this.loading.isActive = false; })
            .catch((err) => {
                this.loading.isActive = false;
                alertService.error(err);
            });
    },
};
</script>
