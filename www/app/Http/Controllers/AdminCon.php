<?php
namespace App\Http\Controllers;

use Auth;
use DB;



use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\User;
use App\Cafe;

use App\DataTables\Buttons\UsersQueryBuilderDataTable;
use PhpParser\Node\Expr\Cast\String_;
use Yajra\Datatables\Datatables;
use Input;
use Yajra\DataTables\Utilities\Request;
use App\Http\Controllers\Redirect;

//use Illuminate\Http\Request;

// use Illuminate\Http\Request;


class AdminCon extends Controller{
	/////////////////////////////////////////
	// 데이타
	/////////////////////////////////////////
									// view 페이지
	private $page      = '';
									// 사용자 정보
	private $uid       = '';

	// 로그인한 사용자 정보
	private $user_id     = '';
	private $user_email     = '';
									// 지갑정보
	private $my_wallet = Array();	// wallet에 넣을 sql용
	private $wallet    = Array();	// view에서 사용할데이터

	private $subject  = Array();

    public function __construct()
    {
        $this->middleware('auth');
    }

	public function index(){




		// 인증체크
		$ret=$this->authcheck();
		if($ret){
			return $ret;
		}



		return view('admin/index', compact('is_admin'));




	}

	// 인증체크
	public function authcheck()
	{

		// 로그인 여부 체크
		if (Auth::check()) {
			// 사용자 정보
			$this->user_id = Auth::user()->id;
			$this->user_email = Auth::user()->email;

		} // 로그인이 필요합니다
		else {
			$error_msg = '로그인이 필요 합니다.';
			return view('error/error', compact('error_msg'));


		}
		return 0;

	}


	// 카페관리 호출 처리
	public function cafe_run($cafe_cmd,Request $request){

		// 인증체크
		$ret=$this->authcheck();
		if($ret){
			return $ret;
		}
		$_CFG=array();
		$_CFG['default_date_format']='Y-m-d';



		// 요청 주소
		$req_path3 = '/'.$request->path(); // 명령까지
		$req_path2 = dirname($req_path3); // id까지
		$req_path1 = dirname($req_path2); // 메뉴


		//$page = $request->input('page',1);
		$page = $request->input('page',1);


		$is_admin=1;



		// 카페 목록을 출력한다
		if($cafe_cmd=='list') {


			$startpadding = 0;
			$padding = 20;

			$list_info = DB::select("select * from cafe order by id DESC limit $startpadding,$padding");
			return view('admin/cafe/list', compact('list_info','req_path1','req_path2','req_path3','page','bbs_id','_CFG','is_admin'));

		}

		// create 카페 생성하기

		if ($cafe_cmd == 'create') {


			$idx = $request->input('idx', '0');
			if (intval($idx) == 0) {
				//$error_msg='idx가 안넘어왔다';
				//return view('error/error', compact('error_msg'));

				//$rows=new \stdClass();
				$edit_mode = 0;
			} // 수정시
			else {

				$edit_mode = 1;
				$rows = DB::select("select * from board_housebook_travel where idx=? limit 1", [$idx]);

			}

			return view('admin/cafe/create', compact('list_info', 'req_path1', 'req_path2', 'req_path3', 'page', 'bbs_id', '_CFG', 'is_admin', 'rows', 'edit_mode'));

		}


		// 카페 등록
		if($cafe_cmd=='create_ok') {
//				echo 1;exit;

			// 생성할 카페 이름과 아이디
			$cafe_name=$request->input('cafe_name','');
			$cafe_aid=$request->input('cafe_aid','');

			// 카페 설명
			$cafe_memo=$request->input('wr_content','');

			if(strlen($cafe_name)==0 || strlen($cafe_aid)==0){
				$error_msg='필수 항목이 빠졌습니다';
				return view('error/error', compact('error_msg'));
			}

			$owner_id=$this->user_id;

			// 카페 클래스를 호출해서 카페를 생성한다
			Cafe::create_cafe($owner_id,$cafe_aid,$cafe_name,$cafe_memo);

			// 이동할 페이지 주소
			$move_url=$req_path2;
			return view('bbs/create_ok', compact('cafe_name','cafe_aid','cafe_memo','move_url'));

		}


		// 카페 삭제
		if($cafe_cmd=='delete') {


			$cafe_id = $request->input('cafe_id', '0');
			if (intval($cafe_id) == 0) {
				//$error_msg='id가 안넘어왔다';
				return view('error/error', compact('error_msg'));
			}

//			$result = DB::delete("delete from board_housebook_travel where idx=?", [$idx]);

			// 카페 클래스를 호출해서 카페를 삭제한다
			Cafe::delete_cafe($cafe_id);


			// 삭제후 이동할 페이지 주소
			$move_url = $req_path2;
			return view('bbs/delete', compact('move_url'));

		}


	}




	// 게시판관리 호출 처리
	public function bbs_run($bbs_cmd,Request $request){

		// 인증체크
		$ret=$this->authcheck();
		if($ret){
			return $ret;
		}


		$_CFG=array();
		$_CFG['default_date_format']='Y-m-d';



		// 요청 주소
		$req_path3 = '/'.$request->path(); // 명령까지
		$req_path2 = dirname($req_path3); // id까지
		$req_path1 = dirname($req_path2); // 메뉴


		//$page = $request->input('page',1);
		$page = $request->input('page',1);


		$is_admin=1;


		// 게시판 목록을 출력한다
		if($bbs_cmd=='list') {


			$startpadding = 0;
			$padding = 20;

			$list_info = DB::select("select * from bbs order by id DESC limit $startpadding,$padding");
			return view('admin/bbs/list', compact('list_info','req_path1','req_path2','req_path3','page','bbs_id','_CFG','is_admin'));

		}

		// create 게시판 생성하기

		if ($bbs_cmd == 'create') {


			$idx = $request->input('idx', '0');
			if (intval($idx) == 0) {

				$edit_mode = 0;
			} // 수정시
			else {

				$edit_mode = 1;
				$rows = DB::select("select * from board_housebook_travel where idx=? limit 1", [$idx]);

			}

			return view('admin/bbs/create', compact('list_info', 'req_path1', 'req_path2', 'req_path3', 'page', 'bbs_id', '_CFG', 'is_admin', 'rows', 'edit_mode'));

		}


		// 게시판 등록
		if($bbs_cmd=='create_ok') {
//				echo 1;exit;

			// 생성할 게시판 이름과 아이디
			$bbs_name=$request->input('bbs_name','');
			$bbs_aid=$request->input('bbs_aid','');

			// 게시판 설명
			$bbs_memo=$request->input('wr_content','');

			if(strlen($bbs_name)==0 || strlen($bbs_aid)==0){
				$error_msg='필수 항목이 빠졌습니다';
				return view('error/error', compact('error_msg'));
			}

			$owner_id=$this->user_id;

			// 카페 클래스를 호출해서 카페를 생성한다
			Cafe::create_bbs($cafe_id,$bbs_name,$bbs_id,$bbs_memo);

			// 이동할 페이지 주소
			$move_url=$req_path2;
			return view('bbs/create_ok', compact('cafe_name','cafe_aid','cafe_memo','move_url'));

		}


		// 카페 삭제
		if($bbs_cmd=='delete') {


			$cafe_id = $request->input('cafe_id', '0');
			if (intval($cafe_id) == 0) {
				//$error_msg='id가 안넘어왔다';
				return view('error/error', compact('error_msg'));
			}



			// 카페 클래스를 호출해서 카페를 삭제한다
			Cafe::delete_cafe($cafe_id);


			// 삭제후 이동할 페이지 주소
			$move_url = $req_path2;
			return view('bbs/delete', compact('move_url'));

		}


	}

    public function write()
	{
		if (Auth::check()) {
			$this->uid = Auth::user()->id;
			$this->email = Auth::user()->email;

			return view('_info/write');

		}else{
			return view('auth/login');
		}

	}
	//글 등록되고 그 페이지를 보여줘야된다.
	public function info_write_ok(request $req)
	{

		//로그인 한 사람들만 글 등록
		if (Auth::check()) {
			$info_subject=$req->input('info_subject');
			$contents=$req->input('contents');
			//print_r($_SERVER);

			$ip=$_SERVER['REMOTE_ADDR'];
			$datatime1 = \Carbon\Carbon::now();
			$datatime1->toDateString();
			$datatime1->toTimeString();
			$datatime=$datatime1;

			// 사용자 정보
			$this->uid = Auth::user()->id;
			$this->email = Auth::user()->email;
			$write_info = db::insert('insert into info(subject,user_idx,ipaddress,contents,datatime,name,user_id) values (?,?,?,?,?,?,?)',[$info_subject,$this->uid = Auth::user()->id,$ip,$contents,$datatime,$this->name = Auth::user()->name,$this->email = Auth::user()->email]);

			return view('_info/write', compact('write_info'));

		}else{
			return view('auth/login');
		}
	}
	//글 수정되고 보여주자
	public function info_update_view(Request $request){
		return view('_info/info_update_view');
	}
	//글 확인
	public function info_baord_view(Request $request){
		$idx=$request->input('idx');
		$info_board_view= DB::select('select * from info where idx =?',[$idx]);
		return view('_info/view',compact('info_board_view'));
	}

	//글 삭제하기
	public function info_delete(Request $request){
		//계정 정보를 갖고와서 본인이 등록한 글 삭제
		if(Auth::check()) {
			// 사용자 정보
			$this->uid = Auth::user()->id;
			$this->email = Auth::user()->email;
			$user_id=$this->email = Auth::user()->email;
			$idx=$request->input('idx');
			$delete= DB::delete('delete from info where idx =? and user_id = ?',[$idx,$user_id]);
			//print_r($delete);
			//exit;
			//삭재는 되는데 다음페이지로 넘어가는 방법을 모르겠습니다.
			return view('/info',compact('delete'));
			//return redirect(compact('delete'))->action('info.index');
		}else{
			return view('auth/login');
		}
	}



}