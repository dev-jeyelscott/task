import { Form, Head, Link, router } from '@inertiajs/react';

import TaskController from '@/actions/App/Http/Controllers/TaskController';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import tasks from '@/routes/tasks';
import { BreadcrumbItem } from '@/types';

interface Task {
    id: number;
    title: string;
    description: string;
    priority: 'low' | 'medium' | 'high';
    severity: 'low' | 'medium' | 'high' | 'critical';
    is_completed: boolean;
    completed_at: string | null;
    due_at: string | null;
    created_at: string;
    updated_at: string;
}

const badgeVariants: Record<string, string> = {
    low: 'bg-gray-100 text-gray-700',
    medium: 'bg-yellow-100 text-yellow-800',
    high: 'bg-orange-100 text-orange-800',
    critical: 'bg-red-100 text-red-700',
};

export default function TaskShow({ task }: { task: Task }) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Tasks', href: tasks.index().url },
        { title: 'Task Details', href: tasks.show(task.id).url },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Task Details" />

            <div className="flex justify-center p-4">
                <Card className="w-full">
                    <CardHeader className="flex flex-col gap-2">
                        <div className="flex items-center justify-between">
                            <CardTitle className="text-2xl">
                                {task.title}
                            </CardTitle>

                            <div className="flex gap-2 text-right">
                                <Badge
                                    variant={
                                        task.is_completed
                                            ? 'default'
                                            : 'outline'
                                    }
                                >
                                    {task.is_completed
                                        ? 'Completed'
                                        : 'Pending'}
                                </Badge>
                                {task.is_completed && (
                                    <p className="text-sm text-muted-foreground">
                                        <span className="font-bold">
                                            {task.completed_at
                                                ? new Date(
                                                      task.completed_at,
                                                  ).toLocaleDateString(
                                                      'en-US',
                                                      {
                                                          month: 'short',
                                                          day: '2-digit',
                                                          year: 'numeric',
                                                          hour: '2-digit',
                                                          minute: '2-digit',
                                                          second: '2-digit',
                                                      },
                                                  )
                                                : '—'}
                                        </span>
                                    </p>
                                )}
                            </div>
                        </div>

                        {task.due_at && (
                            <p className="text-sm text-muted-foreground">
                                Due on{' '}
                                <span className="font-bold">
                                    {new Date(task.due_at).toLocaleDateString(
                                        'en-US',
                                        {
                                            month: 'short',
                                            day: '2-digit',
                                            year: 'numeric',
                                        },
                                    )}
                                </span>
                            </p>
                        )}
                    </CardHeader>

                    <CardContent className="space-y-6">
                        {/* Description */}
                        <div>
                            <h4 className="text-sm font-medium text-muted-foreground">
                                Description
                            </h4>
                            <p className="mt-1 text-sm whitespace-pre-line">
                                {task.description || '—'}
                            </p>
                        </div>

                        {/* Meta */}
                        <div className="grid grid-cols-1 gap-4">
                            <div>
                                <h4 className="text-sm font-medium text-muted-foreground">
                                    Priority
                                </h4>
                                <Badge
                                    className={`text-xs font-semibold capitalize ${
                                        badgeVariants[task.priority] ??
                                        'bg-gray-100 text-gray-700'
                                    }`}
                                >
                                    {task.priority}
                                </Badge>
                            </div>

                            <div>
                                <h4 className="text-sm font-medium text-muted-foreground">
                                    Severity
                                </h4>
                                <Badge
                                    className={`text-xs font-semibold capitalize ${
                                        badgeVariants[task.severity] ??
                                        'bg-gray-100 text-gray-700'
                                    }`}
                                >
                                    {task.severity}
                                </Badge>
                            </div>
                        </div>

                        {/* Footer Actions */}
                        <div className="flex items-center justify-between border-t pt-4">
                            <div className="flex gap-2">
                                <Button
                                    onClick={() =>
                                        router.visit(tasks.edit(task.id).url)
                                    }
                                >
                                    Edit Task
                                </Button>
                                <Form
                                    hidden={task.is_completed == true}
                                    {...TaskController.complete.form(task.id)}
                                    className="inline-block"
                                    resetOnSuccess
                                >
                                    {({ processing }) => (
                                        <div className="flex items-center gap-2">
                                            <Button
                                                type="submit"
                                                disabled={processing}
                                                variant={
                                                    task.is_completed
                                                        ? 'outline'
                                                        : 'secondary'
                                                }
                                            >
                                                {task.is_completed
                                                    ? 'Mark as Pending'
                                                    : 'Mark as Completed'}
                                            </Button>
                                        </div>
                                    )}
                                </Form>
                                <Form
                                    hidden={!task.is_completed}
                                    {...TaskController.reopen.form(task.id)}
                                    className="inline-block"
                                    resetOnSuccess
                                >
                                    {({ processing }) => (
                                        <div className="flex items-center gap-2">
                                            <Button
                                                type="submit"
                                                disabled={processing}
                                                variant={
                                                    task.is_completed
                                                        ? 'outline'
                                                        : 'secondary'
                                                }
                                            >
                                                {task.is_completed
                                                    ? 'Mark as Pending'
                                                    : 'Mark as Completed'}
                                            </Button>
                                            {/* <Transition
                                                show={recentlySuccessful}
                                                enter="transition-opacity duration-300"
                                                enterFrom="opacity-0"
                                                enterTo="opacity-100"
                                                leave="transition-opacity duration-300"
                                                leaveFrom="opacity-100"
                                                leaveTo="opacity-0"
                                            >
                                                <p className="text-sm text-neutral-600">
                                                    Saved
                                                </p>
                                            </Transition> */}
                                        </div>
                                    )}
                                </Form>
                            </div>

                            <Link
                                href={tasks.index().url}
                                className="text-sm text-muted-foreground hover:underline"
                            >
                                Back to Tasks
                            </Link>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
