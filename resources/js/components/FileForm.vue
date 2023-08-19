<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Lets upload your a large csv file</div>
                    <div class="card-body">
                        <form @submit.prevent="onSubmit">
                            <fieldset>
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input
                                            type="file"
                                            class="custom-file-input"
                                            id="customFile"
                                            accept="csv/*"
                                            ref="fileContainer"
                                            @change="onChangeFile">
                                            <label class="custom-file-label" for="customFile" ref="fileLabel">{{ label }}</label>
                                        </div>
                                        <div class="my-3 progress">
                                            <div
                                                class="progress-bar"
                                                role="progressbar"
                                                :style="{ width: progress + '%' }"
                                                aria-valuenow="25"
                                                aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                        <div
                                            class="my-3 alert alert-primary"
                                            :class="{ 'd-none': null === result }"
                                            role="alert">
                                            <span>{{ result }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
        import uploadService from './uploadService.js';

        export default {
            data() {
                return {label: 'Choose File', file: null, progress: 0, result: null};
            },
            methods: {
                onSubmit() {
                    if (null !== this.file) {
                        this.progress = 0;
                        this.result = null;

                        uploadService.chunk('/formSubmit', this.file, percent => {
                            this.progress = percent;
                        }, err => {
                            console.log(err);
                        }, res => {
                            const {data} = res;
                            this.result = data.result;
                        });
                    }
                },
                onChangeFile() {
                    const file = this.$refs.fileContainer.files;
                    this.file = file.length > 0
                        ? file[0]
                        : null;
                    if (null !== this.file) {
                        this.label = `${this.file.name}`;
                    } else {
                        this.label = 'Choose File';
                    }
                }
            }
        }
    </script>