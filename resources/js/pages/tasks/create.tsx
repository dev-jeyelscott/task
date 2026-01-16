import { Textarea } from "@headlessui/react";
import { Form, Head, Link } from "@inertiajs/react";
import { Label } from "@radix-ui/react-label";
import { SelectGroup } from "@radix-ui/react-select";

import TaskController from "@/actions/App/Http/Controllers/TaskController";
import InputError from "@/components/input-error";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Select, SelectContent, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select";
import AppLayout from "@/layouts/app-layout";
import tasks from "@/routes/tasks";
import { BreadcrumbItem } from "@/types";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tasks',
        href: tasks.index().url,
    },
    {
        title: 'Create Task',
        href: tasks.create().url,
    },
]
export default function TaskCreate() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Task" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="p-4 relative flex justify-center min-h-100vh flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                    <div className="w-full">
                        <Card>
                            <CardHeader>
                                <CardTitle>Create Task</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <Form
                                    {...TaskController.store.form()}
                                    options={{
                                        preserveScroll: true,
                                    }}
                                    className="space-y-6"
                                    resetOnSuccess
                                    onError={(errors) => {

                                    }}
                                >
                                    {({ errors , processing }) => (
                                        <>
                                            <div className="grid gap-2">
                                                <Label htmlFor="title">
                                                    Title <span className="text-red-600">*</span>
                                                </Label>
                                                <Input
                                                    id="title"
                                                    name="title"
                                                    className="mt-1 block w-full"
                                                    required
                                                    autoFocus
                                                    placeholder="Title"
                                                />
                                                <InputError message={errors.title} />
                                            </div>

                                            <div className="grid gap-2">
                                                <Label htmlFor="description">
                                                    Description
                                                </Label>
                                                <Input
                                                    type="text"
                                                    id="description"
                                                    name="description"
                                                    className="mt-1 block w-full"
                                                    placeholder="Description"
                                                />
                                                <InputError message={errors.description} />
                                            </div>

                                            <div className="grid gap-2">
                                                <Label>
                                                    Priority
                                                </Label>
                                                <Select name="priority">
                                                    <SelectTrigger>
                                                        <SelectValue placeholder="Select priority" />
                                                    </SelectTrigger>
                                                    <SelectContent>
                                                        <SelectGroup>
                                                            <SelectLabel>Priority</SelectLabel>
                                                            <SelectItem value="low">Low</SelectItem>
                                                            <SelectItem value="medium">Medium</SelectItem>
                                                            <SelectItem value="high">High</SelectItem>
                                                        </SelectGroup>
                                                    </SelectContent>
                                                </Select>
                                                <InputError message={errors.priority} />
                                            </div>

                                            <div className="grid gap-2">
                                                <Label>
                                                    Severity
                                                </Label>
                                                <Select name="severity">
                                                    <SelectTrigger>
                                                        <SelectValue placeholder="Select severity" />
                                                    </SelectTrigger>
                                                    <SelectContent>
                                                        <SelectGroup>
                                                            <SelectLabel>Severity</SelectLabel>
                                                            <SelectItem value="low">Low</SelectItem>
                                                            <SelectItem value="medium">Medium</SelectItem>
                                                            <SelectItem value="high">High</SelectItem>
                                                            <SelectItem value="critical">Critical</SelectItem>
                                                        </SelectGroup>
                                                    </SelectContent>
                                                </Select>
                                                <InputError message={errors.severity} />
                                            </div>

                                            <div>
                                                <Label htmlFor="due_at">Due Date</Label>
                                                <Input
                                                    id="due_at"
                                                    name="due_at"
                                                    type="date"
                                                    className="mt-1 block w-full"
                                                />
                                                <InputError message={errors.due_at} />
                                            </div>

                                            <div className="flex items-center justify-between gap-4">
                                                <Button type="submit" disabled={processing}>
                                                    {processing ? 'Saving...' : 'Save'}
                                                </Button>

                                                <Link href={tasks.index().url} className="text-sm font-medium hover:underline">Cancel</Link>
                                            </div>
                                        </>
                                    )}
                                </Form>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </AppLayout>
    )
}
