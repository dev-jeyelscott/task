import { Head, Link, router } from '@inertiajs/react';
import { useRef } from 'react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import tasks from '@/routes/tasks';
import { BreadcrumbItem } from '@/types';

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

export default function TaskShow({ task }: { task: Task }) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Tasks',
            href: tasks.index().url,
        },
        {
            title: 'Show Task',
            href: tasks.show(task.id).url,
        },
    ];

    const taskTitle = useRef<HTMLInputElement>(null);
    const taskDescription = useRef<HTMLInputElement>(null);
    const taskDueDate = useRef<HTMLInputElement>(null);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Show Task" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="min-h-100vh relative flex flex-1 justify-center overflow-hidden rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border">
                    <div className="w-full">
                        <Card>
                            <CardHeader>
                                <CardTitle>Show Task</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-6">
                                    <div className="grid gap-2">
                                        <Label htmlFor="title">
                                            Title{' '}
                                            <span className="text-red-600">
                                                *
                                            </span>
                                        </Label>
                                        <Input
                                            id="title"
                                            name="title"
                                            disabled
                                            ref={taskTitle}
                                            className="mt-1 block w-full"
                                            required
                                            autoFocus
                                            placeholder="Title"
                                            defaultValue={task.title}
                                        />
                                        <InputError />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="description">
                                            Description
                                        </Label>
                                        <Input
                                            type="text"
                                            id="description"
                                            disabled
                                            ref={taskDescription}
                                            name="description"
                                            className="mt-1 block w-full"
                                            placeholder="Description"
                                            defaultValue={task.description}
                                        />
                                        <InputError />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label>Priority</Label>
                                        <Select
                                            name="priority"
                                            defaultValue={task.priority}
                                            disabled
                                        >
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select priority" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectGroup>
                                                    <SelectLabel>
                                                        Priority
                                                    </SelectLabel>
                                                    <SelectItem value="low">
                                                        Low
                                                    </SelectItem>
                                                    <SelectItem value="medium">
                                                        Medium
                                                    </SelectItem>
                                                    <SelectItem value="high">
                                                        High
                                                    </SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                        <InputError message="" />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label>Severity</Label>
                                        <Select
                                            name="severity"
                                            defaultValue={task.severity}
                                            disabled
                                        >
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select severity" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectGroup>
                                                    <SelectLabel>
                                                        Severity
                                                    </SelectLabel>
                                                    <SelectItem value="low">
                                                        Low
                                                    </SelectItem>
                                                    <SelectItem value="medium">
                                                        Medium
                                                    </SelectItem>
                                                    <SelectItem value="high">
                                                        High
                                                    </SelectItem>
                                                    <SelectItem value="critical">
                                                        Critical
                                                    </SelectItem>
                                                </SelectGroup>
                                            </SelectContent>
                                        </Select>
                                        <InputError />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="due_at">Due Date</Label>
                                        <Input
                                            id="due_at"
                                            name="due_at"
                                            disabled
                                            type="date"
                                            ref={taskDueDate}
                                            className="mt-1 block w-full"
                                            defaultValue={task.due_at ?? ''}
                                        />
                                        <InputError />
                                    </div>

                                    <div className="flex items-center justify-between gap-4">
                                        <Button
                                            onClick={() =>
                                                router.visit(
                                                    tasks.edit(task.id).url,
                                                )
                                            }
                                        >
                                            Edit
                                        </Button>

                                        <Link
                                            href={tasks.index().url}
                                            className="text-sm font-medium hover:underline"
                                        >
                                            Cancel
                                        </Link>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
