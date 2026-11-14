<template>
    <div>
        <a href="#" class="mr-1" data-toggle="modal" :data-target="`#modal-${fileName}`">
            <i class="fas fa-cloud-upload-alt" aria-hidden="true"></i>{{ dialogName }}
        </a>
        <form :action="url" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_token" :value="csrf" />
            <div class="modal fade" :id="`modal-${fileName}`" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center">{{ dialogName }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body input-group">
                            <div class="custom-file text-left">
                                <input type="file" :id="fileName" class="custom-file-input" :name="fileName" accept=".csv" @change="selectFile($event)" />
                                <label class="custom-file-label" :for="fileName" data-browse="参照">{{ selectedFile }}</label>
                            </div>
                            <div class="input-group-append">
                                <button type="button" id="reset" class="btn btn-outline-secondary reset" @click="removeFile($event)">取消</button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                            <button type="submit" name="submit" class="btn btn-info" :disabled="isDisabled">アップロード</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    props: {
        propsFileName: String,
        propsDialogName: String,
        propsUrl: String,
        propsCsrf: String,
    },
    data() {
        return {
            fileName: this.propsFileName,
            dialogName: this.propsDialogName,
            url: this.propsUrl,
            csrf: this.propsCsrf,
            selectedFile: null,
            isDisabled: "disabled",
        };
    },
    created() {
        this.defaultFileName = 'ファイル選択...'
        this.selectedFile = this.defaultFileName
    },
    methods: {
        selectFile(e) {
            const file = e.target.files[0]
            this.selectedFile = file.name
            this.isDisabled = null
        },
        removeFile(e) {
            this.isDisabled = 'disabled'
            this.selectedFile = this.defaultFileName
        },
    },
};
</script>
