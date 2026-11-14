<template>
    <vuejs-datepicker
        :value="this.default"
        :format="DatepickerFormat"
        :name="this.nameAttribute"
        :language="ja"

        input-class="form-control"
        placeholder="日付を選択してください"
    >
    </vuejs-datepicker>
</template>
        <!-- :disabled-dates="{ to: new Date(Date.now() - 8640000) }" -->
<script>
import Datepicker from "vuejs-datepicker";
import {ja} from "vuejs-datepicker/dist/locale";

export default {
    props:{
        defaultDate: String,
        propsName: String,
    },
    data(){
        return {
            DatepickerFormat: 'yyyy-MM-dd',
            default: this.defaultDate,
            ja:ja,
            minimumDate: null,
            nameAttribute: this.propsName,
        }
    },
    components:{
        "vuejs-datepicker": Datepicker,
    },
}
</script>
