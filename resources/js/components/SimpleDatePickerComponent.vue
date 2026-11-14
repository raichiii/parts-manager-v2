<template>
    <vuejs-datepicker
        :value="this.default"
        :format="DatepickerFormat"
        :name="this.nameAttribute"
        :language="ja"
        placeholder="日付を選択してください"
        >
    </vuejs-datepicker>
</template>

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
