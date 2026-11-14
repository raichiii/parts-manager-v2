<template>
    <div>
        <div class="modal fade" id="confirm-order" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center">確認</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        本当に発注しますか？
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">いいえ</button>
                        <button type="submit" name="submit" class="btn btn-success" :formaction="orderUrl">はい</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="confirm-receive" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center">確認</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        本当に受領しますか？
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">いいえ</button>
                        <button type="submit" name="submit" class="btn btn-success" :formaction="receiveUrl">はい</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="confirm-delete" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center">確認</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        本当に削除しますか？
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">いいえ</button>
                        <button type="submit" name="submit" class="btn btn-danger" :formaction="deleteUrl">はい</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        propsOrderUrl: String,
        propsReceiveUrl: String,
        propsDeleteUrl: String,
    },
    data() {
        return {
            orderUrl: this.propsOrderUrl,
            receiveUrl: this.propsReceiveUrl,
            deleteUrl: this.propsDeleteUrl,
        };
    },
};
</script>
