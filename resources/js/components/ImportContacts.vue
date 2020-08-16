<template>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Import Contacts</div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="fileInput"
                                           @change="onFileChange">
                                    <label class="custom-file-label" for="fileInput">{{
                                            file ? file.name : 'Choose File'
                                        }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="button"
                                    class="btn btn-primary"
                                    v-if="file && !data"
                                    @click.prevent="upload">Upload
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-8" v-if="data">
                <div class="card">
                    <div class="card-header">
                        {{ processing ? 'Processing...' : 'Map Fields' }}
                        <button type="button" class="btn btn-primary btn-sm float-right"
                                @click="store()">Process</button>
                    </div>
                    <div class="card-body" v-if="!processing">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row" v-for="(column, index) in data.csvColumns" :key="column">
                                    <div class="col-md-6 form-group">
                                        <input class="form-control" type="text" :value="column">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <v-select v-if="findField(column, index)"
                                                  :value="mappedFields[index]"
                                                  taggable
                                                  :options="data.availableColumns" />
                                        <v-select v-else
                                                  @input="mapField($event, index)"
                                                  taggable
                                                  :options="data.availableColumns" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8" v-if="totalImported && !data">
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Well done!</h4>
                    <p>{{ totalImported }} contacts have been successfully imported!</p>
                    <hr>
                    <p class="mb-0">Go to <a href="/contacts">contacts.</a></p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import vSelect from 'vue-select'
export default {
    components: { vSelect },
    mounted() {
        console.log('Component mounted.')
    },
    data() {
        return {
            file: '',
            config: {
                headers: {'content-type': 'multipart/form-data'}
            },
            form: new FormData(),
            data: '',
            mappedFields: {},
            totalImported: '',
            processing: false
        }
    },
    methods: {
        onFileChange(file) {
            this.clean()
            this.totalImported = ''
            this.file = file.target.files[0]
        },
        upload() {
            this.form.append('file', this.file)
            let that = this
            axios.post('/contacts/upload', this.form, this.config)
                .then( response => {
                    that.data = response.data
                })
                .catch( error => {
                    console.log(error)
                });
        },
        store() {
            this.processing = true
            this.form.append('fields', JSON.stringify(this.mappedFields))
            axios.post('/contacts/store', this.form, this.config)
                .then( (response) => this.totalImported = response.data)
                .catch( (error) => console.log(error))
                .finally( () => {
                    this.clean()
                    this.processing = false
                })
        },
        findField(value, index) {
            let field = _.filter(this.data.availableColumns, item => {
                return item === value
            })[0]
            if (field) {
                this.mapField(value, index)
            }
            return field
        },
        mapField(event, key) {
            this.mappedFields[key] = event
        },
        clean() {
            this.file = ''
            this.data = ''
            this.form = new FormData()
            this.mappedFields = {}
        }
    },
}
</script>
