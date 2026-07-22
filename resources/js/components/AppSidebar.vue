<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BookOpen, FolderGit2, LayoutGrid, Layers, Building2, MapPinned, CircleHelp, Shield, Users, Star, Bookmark, Clock3 } from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { index as categoriesIndex } from '@/routes/categories';
import { index as campusIndex } from '@/routes/campuses';
import { index as placesIndex } from '@/routes/places';
import { index as faqsIndex } from '@/routes/faqs';
import { index as rolesIndex } from '@/routes/roles';
import { index as usersIndex } from '@/routes/users';
import { index as ratingsIndex } from '@/routes/ratings';
import { index as favoritesIndex } from '@/routes/favorites';
import { index as searchHistoryIndex } from '@/routes/search-histories';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Campus',
        href: campusIndex(),
        icon: Building2,
    },
    {
        title: 'Categorías',
        href: categoriesIndex(),
        icon: Layers,
    },
    {
        title: 'Lugares',
        href: placesIndex(),
        icon: MapPinned,
    },
    {
        title: 'Preguntas Frecuentes',
        href: faqsIndex(),
        icon: CircleHelp,
    },
    {
        title: 'Roles',
        href: rolesIndex(),
        icon: Shield,
    },
    {
        title: 'Users',
        href: usersIndex(),
        icon: Users,
    },
    {
        title: 'Ratings',
        href: ratingsIndex(),
        icon: Star,
    },
    {
        title: 'Favoritos',
        href: favoritesIndex(),
        icon: Bookmark,
    },
    {
        title: 'Historial de Búsqueda',
        href: searchHistoryIndex(),
        icon: Clock3,
    }
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: FolderGit2,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
