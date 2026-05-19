const SiteMenuComponent     = () => import("../../components/admin/siteMenus/SiteMenuComponent");
const SiteMenuListComponent = () => import("../../components/admin/siteMenus/SiteMenuListComponent");
const SiteMenuShowComponent = () => import("../../components/admin/siteMenus/SiteMenuShowComponent");

export default [
    {
        path: '/admin/site-menus',
        component: SiteMenuComponent,
        name: 'admin.site-menus',
        redirect: { name: 'admin.site-menus.list' },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'settings',
            breadcrumb: 'site_menus',
        },
        children: [
            {
                path: '',
                component: SiteMenuListComponent,
                name: 'admin.site-menus.list',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'settings',
                    breadcrumb: '',
                },
            },
            {
                path: 'show/:id',
                component: SiteMenuShowComponent,
                name: 'admin.site-menus.show',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'settings',
                    breadcrumb: 'view',
                },
            },
        ],
    },
]
