<template>
    <div>
        <div ref="modalBg" class="modal-bg">
            &nbsp;
        </div>
        <div ref="modalContainer" class="modal-container">
            <div ref="modalMask" :class="positionFixed ? 'modal-mask-fixed' : 'modal-mask'">
                <div class="modal-wrapper" :style="modalStyle">
                    <div class="modal-title" ref="modalTitle" v-if="showTitle">
                        <span id="ui-id-1" class="ui-dialog-title">{{ title }}</span>
                    </div>
                    <div class="btn-close" v-if="showExit" @click="$emit('exit')"></div>

                    <div class="modal-header" v-if="showHeader">
                        <slot name="header">
                        </slot>
                    </div>

                    <div id="modal-body" class="modal-body">
                        <slot name="body">
                        </slot>
                    </div>

                    <div class="modal-footer" v-if="showFooter">
                        <button type="button" v-if="showOk" @click="$emit('submit')"
                                class="addButtonClass modal-default-button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                                role="button">
                            <slot name="submit">
                            </slot>
                        </button>
                        <button type="button" v-if="showNeutral" @click="$emit('neutral')"
                                class="addButtonClass modal-default-button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"
                                role="button">
                            <slot name="neutral">
                            </slot>
                        </button>
                        <button type="button" v-if="showCancel" @click="$emit('close')"
                                class="cancelButtonClass modal-default-button ui-button ui-widget ui-state-default
                                ui-corner-all ui-button-text-only" role="button">
                            <slot name="cancel">
                            </slot>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "modal",
    props: {
        title: String,
        width: String,
        showOk: {Boolean, default: true},
        showExit: {Boolean, default: false},
        showHeader: {Boolean, default: true},
        showFooter: {Boolean, default: true},
        showTitle: {Boolean, default: true},
        showNeutral: {Boolean, default: false},
        showCancel: {Boolean, default: false},
        // todo: remove positionFixed - was used in only one modal
        positionFixed: {Boolean, default: false},
        setZIndex: null
    },
    mounted() {
        this.scrollToModal();
        if (this.setZIndex != null) {
            let modalBg = this.$refs.modalBg;
            modalBg.style.zIndex = this.setZIndex;
        }
    },
    methods: {
        scrollToModal() {

            let modalContainer = this.$refs.modalContainer;
            let modalMask = this.$refs.modalMask;
            modalMask.style.width = this.width + 'px';

            let leftValue = (document.documentElement.clientWidth - parseInt(this.width)) / 2 - modalMask.getBoundingClientRect().left;
            modalMask.style.left = leftValue + 'px';

            if (document.documentElement.clientHeight < modalMask.clientHeight + 50) {
                modalContainer.style.top = -modalContainer.getBoundingClientRect().top + 'px';
                modalContainer.style.marginTop = 70 + 'px';
            } else {
                let wynik = (document.documentElement.clientHeight - modalMask.clientHeight) / 2 - modalMask.getBoundingClientRect().top;
                modalMask.style.top = wynik + 'px';
            }
        },
    },
    computed: {
        modalStyle() {
            return 'width: ' + this.width;
        }
    },
}
</script>

<style scoped>
.modal-bg {
    position: fixed;
    z-index: 9997;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, .5);
    transition: opacity .3s ease;
}

.modal-container {
    position: absolute;
    z-index: 9998;
    top: 0;
    left: 0;
}

.modal-mask {
    position: absolute;
    z-index: 9999;
    top: 0;
    left: 0;
}
/* todo: remove positionFixed - was used in only one modal */
.modal-mask-fixed {
    position: absolute;
    z-index: 9999;
    top: 0;
    left: 0;
}

.modal-wrapper {
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
    transition: all .3s ease;
    font-family: Helvetica, Arial, sans-serif;
}
.modal-title {
    font-weight: bold;
    background: #e4e4e4;
    padding: 17px;
    color: #8C8C8C;
    border-bottom: #D2D2D2 1px solid;
    border-top-right-radius: 2px;
    border-top-left-radius: 2px;
}
.btn-close {
    position: absolute;
    width: 53px;
    height: 53px;
    background: #af0530;
    top: 0;
    right: 0;
    color: #fff;
    border-top-right-radius: 2px;
    cursor: pointer;
    transition: background 0.25s;
}
.btn-close::after {
    content: '+';
    font-size: 30px;
    line-height: 30px;
    font-weight: bold;
    display: block;
    width: 30px;
    height: 30px;
    position: relative;
    top: 15px;
    left: 15px;
    transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    -moz-transform: rotate(45deg);
    -webkit-transform: rotate(45deg);
    -o-transform: rotate(45deg);
}
.btn-close:hover {
    background: #9c052b;
}
.modal-header h3 {
    margin-top: 0;
    color: #42b983;
}

.modal-body {
    margin: 20px 0;
}

.modal-default-button {
    float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
    opacity: 0;
}

.modal-leave-active {
    opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
}
</style>
