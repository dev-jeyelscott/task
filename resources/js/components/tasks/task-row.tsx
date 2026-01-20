import { Form } from '@inertiajs/react';

import TaskController from '@/actions/App/Http/Controllers/TaskController';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTrigger,
} from '@/components/ui/dialog';
import tasks from '@/routes/tasks';

interface Task {
    id: number;
    title: string;
    description: string;
    priority: string;
    severity: string;
    is_completed: boolean;
    completed_at: string | null;
    due_at: string | null;
}

const priorityVariants: Record<string, string> = {
    low: 'bg-gray-100 text-gray-700',
    medium: 'bg-yellow-100 text-yellow-800',
    high: 'bg-orange-100 text-orange-800',
};

const severityVariants: Record<string, string> = {
    low: 'bg-gray-100 text-gray-700',
    medium: 'bg-yellow-100 text-yellow-800',
    high: 'bg-orange-100 text-orange-800',
    critical: 'bg-red-100 text-red-700',
};

const isCompletedVariants: Record<string, string> = {
    true: 'bg-green-100 text-green-800',
    false: 'bg-gray-100 text-gray-700',
};

export default function TaskRow({ task }: { task: Task }) {
    return (
        <tr key={task.id} className="transition hover:bg-gray-50">
            <td className="px-4 py-3 font-medium text-gray-900">
                <TextLink href={tasks.show(task.id).url}>{task.title}</TextLink>
            </td>

            <td className="px-4 py-3 text-gray-600">
                <span title={task.description} className="line-clamp-2">
                    {task.description}
                </span>
            </td>

            <td className="px-4 py-3 text-center">
                <span
                    className={`inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold capitalize ${
                        priorityVariants[task.priority] ??
                        'bg-gray-100 text-gray-700'
                    }`}
                >
                    {task.priority}
                </span>
            </td>

            <td className="px-4 py-3 text-center">
                <span
                    className={`inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold capitalize ${
                        severityVariants[task.severity] ??
                        'bg-gray-100 text-gray-700'
                    }`}
                >
                    {task.severity}
                </span>
            </td>

            <td className="px-4 py-3 text-center text-gray-600">
                {task.due_at
                    ? new Date(task.due_at).toLocaleDateString('en-US', {
                          month: 'short',
                          day: '2-digit',
                          year: 'numeric',
                      })
                    : '—'}
            </td>

            <td className="px-4 py-3 text-center">
                <span
                    className={`inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold capitalize ${
                        isCompletedVariants[
                            task.is_completed ? 'true' : 'false'
                        ] ?? 'bg-gray-100 text-gray-700'
                    }`}
                >
                    {task.is_completed ? 'Completed' : 'Pending'}
                </span>
            </td>

            <td className="space-x-2 px-4 py-3 text-center">
                <Button size="sm" variant="outline" asChild>
                    <TextLink
                        href={tasks.edit(task.id).url}
                        className="no-underline"
                    >
                        Edit
                    </TextLink>
                </Button>
                <Dialog>
                    <DialogTrigger asChild>
                        <Button size="sm" variant="destructive">
                            Delete
                        </Button>
                    </DialogTrigger>
                    <DialogContent>
                        <DialogHeader>
                            Are you sure you want to delete this task?
                        </DialogHeader>
                        <Form
                            {...TaskController.destroy.form(task)}
                            options={{
                                preserveScroll: true,
                            }}
                            className="space-y-6"
                            resetOnSuccess
                        >
                            {({ resetAndClearErrors, processing }) => (
                                <>
                                    <DialogFooter>
                                        <DialogClose asChild>
                                            <Button
                                                variant="secondary"
                                                onClick={() =>
                                                    resetAndClearErrors()
                                                }
                                            >
                                                Cancel
                                            </Button>
                                        </DialogClose>
                                        <Button
                                            type="submit"
                                            variant="destructive"
                                            disabled={processing}
                                        >
                                            Delete
                                        </Button>
                                    </DialogFooter>
                                </>
                            )}
                        </Form>
                    </DialogContent>
                </Dialog>
            </td>
        </tr>
    );
}
