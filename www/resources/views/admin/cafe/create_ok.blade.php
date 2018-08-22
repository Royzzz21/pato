@extends('layouts.coin.app')
@section('content')
<section>
    <div>
            다음과 같이 등록되엇습니다
name: <?=$cafe_name?>
id: <?=$cafe_aid?>
    </div>

            <table align="center">
                <tr>

                    <td>


                        <a href="<?=$move_url?>/list">리스트로 이동</a>

                    </td>
                </tr>

            </table>

</section>
@endsection