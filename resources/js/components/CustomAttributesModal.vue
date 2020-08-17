<template>
    <div>
        <!-- Modal -->
        <transition name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ contact.name }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true" @click="close">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row" v-for="attribute in contact.custom_attributes" :key="attribute.id">
                                    <div class="col-md-4">{{ attribute.key | titleCase }}</div>
                                    <div class="col-md-8">{{ attribute.value }}</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button @click="close" type="button" class="btn btn-secondary">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        contact: {
            type: Object,
        },
    },
    filters: {
        titleCase: text => {
            let sentence = text.toLowerCase().split("_");
            for (let i = 0; i < sentence.length; i++) {
                sentence[i] = sentence[i][0].toUpperCase() + sentence[i].slice(1);
            }

            return sentence.join(" ");
        }
    },
    methods: {
        close() {
            this.$emit('close');
        }
    },
}
</script>

<style scoped>
.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, .5);
    display: table;
    transition: opacity .3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
}
</style>
