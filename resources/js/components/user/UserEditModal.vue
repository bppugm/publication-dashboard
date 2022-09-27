<template>
    <div
        id="user-edit-modal"
        class="modal fade"
        tabindex="-1"
        aria-labelledby="userEditModal"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="mb-3 required-field">
                        <label for="name" class="form-label">Nama</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            class="form-control"
                            placeholder="Enter Name"
                            v-model="form.name"
                            :class="{ 'is-invalid': hasErrors('name') }"
                        />
                        <div class="invalid-feedback">
                            {{ getErrors("name") }}
                        </div>
                    </div>
                    <div class="mb-3 required-field">
                        <label for="email" class="form-label">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="form-control"
                            placeholder="Enter Email"
                            v-model="form.email"
                            :class="{ 'is-invalid': hasErrors('email') }"
                        />
                        <div class="invalid-feedback">
                            {{ getErrors("email") }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label"
                            >Password</label
                        >
                        <div class="input-group">
                            <input
                                id="password"
                                name="password"
                                :type="showPassword ? 'text' : 'password'"
                                class="form-control icon-append"
                                placeholder="Enter Password"
                                v-model="form.password"
                                :class="{
                                    'is-invalid': hasErrors('password'),
                                }"
                            />
                            <div
                                class="d-flex icon-input-append h-100 px-3"
                                @click="showPassword = !showPassword"
                            >
                                <i
                                    class="my-auto"
                                    :class="
                                        showPassword
                                            ? 'mdi mdi-eye-off'
                                            : 'mdi mdi-eye'
                                    "
                                ></i>
                            </div>
                        </div>
                        <div class="invalid-feedback">
                            {{ getErrors("password") }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label"
                            >Password Confirmation</label
                        >
                        <div class="input-group">
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                :type="
                                    showPasswordConfirmation
                                        ? 'text'
                                        : 'password'
                                "
                                class="form-control icon-append"
                                placeholder="Enter Password Confirmation"
                                v-model="form.password_confirmation"
                                :class="{
                                    'is-invalid': hasErrors(
                                        'password_confirmation'
                                    ),
                                }"
                            />
                            <div
                                class="d-flex icon-input-append h-100 px-3"
                                @click="
                                    showPasswordConfirmation =
                                        !showPasswordConfirmation
                                "
                            >
                                <i
                                    class="my-auto"
                                    :class="
                                        showPasswordConfirmation
                                            ? 'mdi mdi-eye-off'
                                            : 'mdi mdi-eye'
                                    "
                                ></i>
                            </div>
                        </div>
                        <div class="invalid-feedback">
                            {{ getErrors("password_confirmation") }}
                        </div>
                    </div>
                </div>
                <!-- Submit  -->
                <div class="modal-footer border-0">
                    <div class="d-flex w-100">
                        <button
                            type="button"
                            class="btn btn-danger w-50 me-2"
                            @click="resetForm"
                        >
                            Reset
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary w-50 ms-2"
                            :disabled="isLoading"
                            @click="submitForm"
                        >
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        selectedData: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            form: {
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
            },
            showPassword: false,
            showPasswordConfirmation: false,
            errors: {},
            isLoading: false,
        };
    },
    watch: {
        selectedData: {
            immediate: true,
            handler() {
                this.initModal();
            },
        },
    },
    methods: {
        initModal() {
            this.form = { ...this.selectedData };
            this.errors = {};
        },
        resetForm() {
            this.form = { ...this.selectedData };
        },
        doSubmit() {
            this.submitForm();
        },
        async submitForm() {
            this.isLoading = true;
            try {
                await axios.put(`/user/${this.selectedData.id}`, this.form);
                return location.reload();
            } catch (error) {
                this.errors = error.response.data.errors;
            } finally {
                this.isLoading = false;
            }
        },
        hasErrors(key) {
            if (this.errors[key]) {
                return true;
            }
            return false;
        },
        getErrors(key) {
            if (this.hasErrors(key)) {
                return this.errors[key].join(", ");
            }
            return "";
        },
    },
};
</script>

<style scoped></style>
