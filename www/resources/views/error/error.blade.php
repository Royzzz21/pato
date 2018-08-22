@extends('layouts.coin.app')
@section('content')

    <section class="content">
        <div class="clearfix">
            <div class="aboutus-top">
                <h2 class="font-30">정보2</h2>
            </div>
            <div>

            </div>
            <div>
                에러가 발생했습니다 불편을 드려 죄송합니다.
                <br>
                <?


                    // 에러메시지 지정되어있으면 출력한다
                if(isset($error_msg)){
                    echo $error_msg;


                }

                ?>


                <table>
                    <tr>

                        <td>
                            <a href="/">첫페이지로 돌아가기</a>

                        </td>
                    </tr>

                </table>
            </div>
        </div>
    </section>
@endsection