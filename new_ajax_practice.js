// 「クリア」処理用変数
let recentData = [];

$(function(){

    // ページの読み込みが終わったら、phpによるDB読み込み値を最終登録情報として保存する
    recentData = GetInputData();

});

function RegisterData(){

    // POST用変数の用意
    postData = {};
    postData['proc'] = 'register';

    // POST用変数に入力値のデータをセット
    postData['data'] = GetInputData();

    // 非同期処理
    $.ajax({

        url : "new_main.php",
        type: "POST",
        data: postData,
        dataType: "json",

    })
    .done(function(data){

        // 最終登録情報を保存
        recentData = GetInputData();

        alert('登録が完了しました');

    })
    .fail(function(data){

        alert('接続エラー');

    });

}

function Clear()
{
    // テキストボックスとチェックボックスの値を、最終登録情報に置き換える
    for(let i = 1; i <= 5; i++)
    {
        $(`#input${i}`).val(recentData[i-1]['text']);
        
        if(recentData[i-1]['flag'] == 1)
        {
            $(`#check${i}`).prop('checked', true);
        }else
        {
            $(`#check${i}`).prop('checked', false);
        }
    }    
}

function GetInputData()
{
    // 入力値の取得
    let inputData = [];
    for(let i = 1; i <= 5; i++)
    {
        // テキストボックスの入力値を取得
        inputText = $(`#input${i}`).val();

        // チェックボックスの入力値を取得（テキストボックスが未入力ならチェック無しとして取得する）
        if($(`#input${i}`).val() !== '' && $(`#check${i}`).prop('checked'))
        {
            inputFlag = 1;
        }
        else
        {
            $(`#check${i}`).prop('checked', false);
            inputFlag = 0;
        }

        // 入力値のデータを１つの配列にまとめる
        inputData.push({'id':i, 'number':999, 'text':inputText, 'flag':inputFlag, 'ref_num':i});
    }

    return inputData;
}