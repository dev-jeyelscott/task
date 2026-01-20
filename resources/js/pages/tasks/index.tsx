import { Head, InfiniteScroll } from '@inertiajs/react';

import TaskRow from '@/components/tasks/task-row';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import tasks from '@/routes/tasks';
import { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tasks',
        href: tasks.index().url,
    },
];

interface Task {
    id: number;
    title: string;
    description: string;
    priority: string;
    severity: string;
    is_completed: boolean;
    completed_at: string | null;
    due_at: string | null;
    created_at: string;
    updated_at: string;
}

interface Props {
    taskItems: {
        data: Task[];
    };
}

export default function TaskIndex({ taskItems }: Props) {
    const sortedTasks = [...taskItems.data].sort((a, b) => {
        if (a.is_completed === b.is_completed) return 0;
        return a.is_completed ? 1 : -1;
    });

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Tasks" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="min-h-100vh relative flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border">
                    <div className="flex justify-end">
                        <Button asChild>
                            <TextLink
                                className="no-underline"
                                href={tasks.create().url}
                            >
                                Create Task
                            </TextLink>
                        </Button>
                    </div>
                    <div className="mt-4 w-full overflow-y-auto">
                        <InfiniteScroll
                            data="taskItems"
                            itemsElement="#tasks-table-body"
                            startElement="#tasks-table-header"
                            endElement="#tasks-table-footer"
                            buffer={300}
                            preserveUrl={true}
                            loading={() => 'Loading more tasks...'}
                        >
                            <div className="h-[75vh] overflow-y-auto rounded-xl border border-gray-200">
                                <table className="w-full border-collapse text-sm">
                                    <thead className="sticky top-0 z-10 bg-gray-50 text-left text-xs font-semibold tracking-wide text-gray-600 uppercase">
                                        <tr>
                                            <th className="px-4 py-3">Title</th>
                                            <th className="px-4 py-3">
                                                Description
                                            </th>
                                            <th className="px-4 py-3 text-center">
                                                Priority
                                            </th>
                                            <th className="px-4 py-3 text-center">
                                                Severity
                                            </th>
                                            <th className="px-4 py-3 text-center">
                                                Due Date
                                            </th>
                                            <th className="px-4 py-3 text-center">
                                                Status
                                            </th>
                                            <th className="px-4 py-3 text-center">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody
                                        id="tasks-table-body"
                                        className="divide-y divide-gray-100"
                                    >
                                        {sortedTasks.map((task: Task) => (
                                            <TaskRow
                                                key={task.id}
                                                task={task}
                                            />
                                        ))}
                                    </tbody>

                                    <tfoot
                                        id="tasks-table-footer"
                                        className="bg-gray-50 text-left text-xs font-semibold tracking-wide text-gray-600 uppercase"
                                    >
                                        <tr>
                                            <th
                                                colSpan={6}
                                                className="text-center"
                                            >
                                                &nbsp;
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </InfiniteScroll>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
