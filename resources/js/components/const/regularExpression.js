//正規表現で用いる定数

const hiragana = /^[ぁ-んー]*$/ //ひらがな
const numeric =  /^[0-9]+$/
const alphanumeric = /^[a-zA-Z0-9]+$/ //英数字
const alphanumericSymbols = /^[a-zA-Z0-9!-/:-@¥[-`{-~\s]*$/
const alphanumericName = /^(([A-Za-z]+[\-\']?)*([A-Za-z]+)?\s)+([A-Za-z]+[\-\']?)*([A-Za-z]+)?$/
const url = /http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w-.\/?%&=]*)?/ //url
const phone = /^[0-9\-]+$/ //電話番号
const email = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/ //メールアドレス
const postalcode = /^[0-9]{3}-[0-9]{4}$/ //郵便番号

export default {
    hiragana: hiragana,
    numeric: numeric,
    alphanumeric: alphanumeric,
    alphanumericSymbols: alphanumericSymbols,
    alphanumericName: alphanumericName,
    url: url,
    phone: phone,
    email: email,
    postalcode: postalcode,
}
