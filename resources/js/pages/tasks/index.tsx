import { Form, Head, InfiniteScroll } from "@inertiajs/react";

import TaskController from "@/actions/App/Http/Controllers/TaskController";
import TextLink from "@/components/text-link";
import { Button } from "@/components/ui/button";
import { Dialog, DialogClose, DialogContent, DialogFooter, DialogHeader, DialogTrigger } from "@/components/ui/dialog";
import AppLayout from "@/layouts/app-layout";
import tasks from "@/routes/tasks";
import { BreadcrumbItem } from "@/types";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tasks',
        href: tasks.index().url,
    },
];

const badgeVariants: Record<string, string> = {
    low: "bg-gray-100 text-gray-700",
    medium: "bg-yellow-100 text-yellow-800",
    high: "bg-orange-100 text-orange-800",
    critical: "bg-red-100 text-red-700",
};

interface Task {
    id: number;
    title: string;
    description: string;
    priority: string;
    severity: string;
    is_completed: boolean;
    completed_at: string|null;
    due_date: string|null;
    created_at: string;
    updated_at: string
}

interface Props {
    taskItems: {
        data: Task[];
    };
}


export default function TaskIndex({taskItems} : Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Tasks" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="p-4 relative min-h-100vh flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                    <div className="flex justify-end">
                        <Button asChild>
                            <TextLink className="no-underline" href={tasks.create().url}>
                                Create Task
                            </TextLink>
                        </Button>
                    </div>
                    <div className="w-full mt-4 overflow-y-auto" >
                        <InfiniteScroll
                            data="taskItems"
                            itemsElement="#tasks-table-body"
                            startElement="#tasks-table-header"
                            endElement="#tasks-table-footer"
                            buffer={300}
                            preserveUrl={true}
                            loading={() => "Loading more tasks..."}
                        >
                            <div className="h-[75vh] overflow-y-auto rounded-xl border border-gray-200">
                                <table className="w-full border-collapse text-sm">
                                    <thead className="sticky top-0 z-10 bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                                        <tr>
                                            <th className="px-4 py-3">Title</th>
                                            <th className="px-4 py-3">Description</th>
                                            <th className="px-4 py-3 text-center">Priority</th>
                                            <th className="px-4 py-3 text-center">Severity</th>
                                            <th className="px-4 py-3">Due Date</th>
                                            <th className="px-4 py-3 text-right">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody  id="tasks-table-body" className="divide-y divide-gray-100">
                                        {taskItems.data.map((task: any) => (
                                            <tr
                                                key={task.id}
                                                className="transition hover:bg-gray-50"
                                            >
                                                <td className="px-4 py-3 font-medium text-gray-900">
                                                    {task.title}
                                                </td>

                                                <td className="px-4 py-3 text-gray-600">
                                                    <span
                                                        title={task.description}
                                                        className="line-clamp-2"
                                                    >
                                                        {task.description}
                                                    </span>
                                                </td>

                                                <td className="px-4 py-3 text-center">
                                                    <span
                                                        className={`inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold capitalize ${
                                                            badgeVariants[task.priority] ?? "bg-gray-100 text-gray-700"
                                                        }`}
                                                    >
                                                        {task.priority}
                                                    </span>
                                                </td>

                                                <td className="px-4 py-3 text-center">
                                                    <span
                                                        className={`inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold capitalize ${
                                                            badgeVariants[task.severity] ?? "bg-gray-100 text-gray-700"
                                                        }`}
                                                    >
                                                        {task.severity}
                                                    </span>
                                                </td>


                                                <td className="px-4 py-3 text-gray-600">
                                                    {task.due_at}
                                                </td>

                                                <td className="px-4 py-3 text-right space-x-2">
                                                    <Button
                                                        size="sm"
                                                        variant="outline"
                                                        asChild
                                                    >
                                                        <TextLink
                                                            href={tasks.edit(task.id).url}
                                                            className="no-underline"
                                                        >
                                                            Edit
                                                        </TextLink>
                                                    </Button>
                                                    <Dialog>
                                                        <DialogTrigger asChild>
                                                            <Button
                                                                size="sm"
                                                                variant="destructive"
                                                            >
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
                                                                {({ resetAndClearErrors, processing, errors }) => (
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
                                        ))}
                                    </tbody>

                                    <tfoot id="tasks-table-footer" className=" bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                                        <tr>
                                            <th colSpan={6} className="text-center">&nbsp;</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </InfiniteScroll>
                    </div>
                </div>
            </div>
        </AppLayout>
    )
}
