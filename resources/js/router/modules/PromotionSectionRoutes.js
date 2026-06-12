const PromotionSectionComponent = () => import("../../components/admin/promotionSections/PromotionSectionComponent");
const PromotionSectionListComponent = () => import("../../components/admin/promotionSections/PromotionSectionListComponent");
const PromotionSectionShowComponent = () => import("../../components/admin/promotionSections/PromotionSectionShowComponent");

export default [
    {
        path: '/admin/promotion-sections',
        component: PromotionSectionComponent,
        name: 'admin.promotion-sections',
        redirect: { name: 'admin.promotion-sections.list' },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'promotions',
            breadcrumb: 'promotion_sections'
        },
        children: [
            {
                path: '',
                component: PromotionSectionListComponent,
                name: 'admin.promotion-sections.list',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'promotions',
                    breadcrumb: ''
                },
            },
            {
                path: 'show/:id',
                component: PromotionSectionShowComponent,
                name: 'admin.promotion-sections.show',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'promotions',
                    breadcrumb: 'view'
                },
            },
        ]
    }
]
