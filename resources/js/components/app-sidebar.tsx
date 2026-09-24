import { Link, usePage } from '@inertiajs/react';
import { Building2, LayoutGrid, Network } from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
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
import adminRoutes from '@/routes/admin';
import instructorRoutes from '@/routes/instructor';
import studentRoutes from '@/routes/student';
import type { NavItem, UserRole } from '@/types';

const navItemsByRole: Record<UserRole, NavItem[]> = {
    admin: [
        {
            title: 'Dashboard',
            href: adminRoutes.dashboard(),
            icon: LayoutGrid,
        },
        {
            title: 'Faculties',
            href: adminRoutes.faculties.index(),
            icon: Building2,
        },
        {
            title: 'Departments',
            href: adminRoutes.departments.index(),
            icon: Network,
        },
    ],
    instructor: [
        {
            title: 'Dashboard',
            href: instructorRoutes.dashboard(),
            icon: LayoutGrid,
        },
    ],
    student: [
        {
            title: 'Dashboard',
            href: studentRoutes.dashboard(),
            icon: LayoutGrid,
        },
    ],
};

export function AppSidebar() {
    const { auth } = usePage().props;
    const role = auth.user?.role;
    const navItems = role ? navItemsByRole[role] : [];
    const home = role ? navItemsByRole[role][0].href : dashboard();

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={home} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={navItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
