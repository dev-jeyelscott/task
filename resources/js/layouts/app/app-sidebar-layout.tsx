import { Transition } from '@headlessui/react';
import { usePage } from '@inertiajs/react';
import {useEffect, useState, type PropsWithChildren } from 'react';

import { AppContent } from '@/components/app-content';
import { AppShell } from '@/components/app-shell';
import { AppSidebar } from '@/components/app-sidebar';
import { AppSidebarHeader } from '@/components/app-sidebar-header';
import { SharedData, type BreadcrumbItem } from '@/types';

const toastStyles: Record<string, string> = {
    success: 'bg-green-200',
    error: 'bg-red-200',
    info: 'bg-blue-200',
    warning: 'bg-yellow-200',
    default: 'bg-gray-200',

};

export default function AppSidebarLayout({
    children,
    breadcrumbs = [],
}: PropsWithChildren<{ breadcrumbs?: BreadcrumbItem[] }>) {
    const { flash } = usePage<SharedData>().props;

    const toastClass =
        toastStyles[flash.toast?.type ?? 'default'];

    const [visible, setVisible] = useState(false);

    useEffect(() => {
        if (flash.toast) {
            setVisible(true);

            const timer = setTimeout(() => {
                setVisible(false);
            }, 3000);

            return () => clearTimeout(timer);
        }
    }, [flash.toast]);

    return (
        <AppShell variant="sidebar">
            <AppSidebar />
            <AppContent variant="sidebar" className="overflow-x-hidden">
                <AppSidebarHeader breadcrumbs={breadcrumbs} />
                <Transition
                    show={visible}
                    enter="transition transform duration-300 ease-out"
                    enterFrom="opacity-0 translate-y-3 scale-95"
                    enterTo="opacity-100 translate-y-0 scale-100"
                    leave="transition transform duration-200 ease-in"
                    leaveFrom="opacity-100 translate-y-0 scale-100"
                    leaveTo="opacity-0 translate-y-3 scale-95"
                >
                    <div className="fixed right-4 top-4 z-50">
                        <p
                            className={`pointer-events-auto rounded-lg px-4 py-3 text-sm font-medium shadow-xl ${toastClass}`}
                        >
                            {flash.toast?.message}
                        </p>
                    </div>
                </Transition>
                {children}
            </AppContent>
        </AppShell>
    );
}
