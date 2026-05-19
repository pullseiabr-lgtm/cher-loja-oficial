const CategorySectionComponent = () => import("../../components/admin/categorySections/CategorySectionComponent");
const CategorySectionListComponent = () => import("../../components/admin/categorySections/CategorySectionListComponent");
const CategorySectionShowComponent = () => import("../../components/admin/categorySections/CategorySectionShowComponent");

export default [
    {
        path: '/admin/category-sections',
        component: CategorySectionComponent,
        name: 'admin.category-sections',
        redirect: { name: 'admin.category-sections.list' },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'settings',
            breadcrumb: 'category_sections'
        },
        children: [
            {
                path: '',
                component: CategorySectionListComponent,
                name: 'admin.category-sections.list',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'settings',
                    breadcrumb: ''
                },
            },
            {
                path: 'show/:id',
                component: CategorySectionShowComponent,
                name: 'admin.category-sections.show',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'settings',
                    breadcrumb: 'view'
                },
            },
        ]
    }
]
