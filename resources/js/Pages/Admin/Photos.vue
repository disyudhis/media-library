<template>
    <app-layout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Photos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- All posts goes here -->
                <h1 class="text-2xl">Photos</h1>
                <a class="px-4 bg-sky-900 text-white rounded-md" :href="route('admin.photos.create')">Create</a>
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                ID</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Photos</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Description</th>
                                            <th scope="col" class="relative px-6 py-3">
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="photo in photos" :key="photo.id">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ photo . id }}</div>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img class="h-10 w-10 rounded-full" :src="photo.path"
                                                            alt />
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ photo . description . slice(0, 100) + '...' }}
                                                </div>
                                            </td>
                                            <!-- ACTIONS -->
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900">
                                                    View - Edit -

                                                    <DangerButton @click="delete_photo(photo)">
                                                        Delete
                                                    </DangerButton>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <DialogModal :show="data.show_modal">
            <template #title>
                Photo {{ data . photo . description . slice(0, 20) + '...' }}
            </template>
            <template #content>
                Are you sure you want to delete this photo?

            </template>
            <template #footer>
                <button @click="closeModal" class="px-4 py-2">Close</button>
                <form @submit.prevent="deleting_photo(data.photo.id)">
                    <DangerButton type="submit">Yes, I am sure!</DangerButton>
                </form>
            </template>
        </DialogModal>
    </app-layout>
</template>

<script>
    import {
        defineComponent
    } from "vue";
    import AppLayout from "@/Layouts/AppLayout.vue";
    import {
        Link,
        useForm
    } from '@inertiajs/vue3';
    import {
        ref
    } from "vue";
    import DialogModal from "@/Components/DialogModal.vue";
    import DangerButton from "@/Components/DangerButton.vue";
    export default defineComponent({
        components: {
            AppLayout,
            Link,
            DialogModal,
            DangerButton
        },
        props: {
            photos: Array,
        },

        setup() {

            const form = useForm({
                _method: "DELETE",
            });
            const data = ref({
                show_modal: false,
                photo: {
                    id: null,
                    path: null,
                    description: null,
                }

            })


            const delete_photo = (photo) => {
                console.log(photo.id, photo.path, photo.description);
                data.value = {
                    photo: {
                        id: photo.id,
                        path: photo.path,
                        description: photo.description
                    },
                    show_modal: true
                };
            }
            const deleting_photo = (id) => {
                form.post(route('admin.photos.delete', id))
                closeModal();
            }

            const closeModal = () => {
                data.value.show_modal = false;


            }

            return {
                form,
                data,
                closeModal,
                delete_photo,
                deleting_photo
            }

        }
    });
</script>
