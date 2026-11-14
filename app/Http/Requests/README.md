# FormRequest

## 役割
データの新規登録（store）や更新（update）のために送信されたPOSTリクエストをバリデーションする。

## 注意事項
バリデーション適用の前にprepareForValidation関数で必要な変数が足される。
例えば、PartRequest.phpのprepareForValidation関数では、生のリクエストデータにはmaker_idの情報はない。したがって、makerの名前からmaker_idを割り出してからバリデーションを通る。
