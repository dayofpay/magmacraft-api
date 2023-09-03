{{-- PHP Variables --}}
@php
    $clanData = DB::connection('#hidden')
        ->table('#hidden')
        ->where('name', $clanName)
        ->first();

    if (!$clanData) {
        return response()->json(
            [
                'MagmaServices.ClanException' => 'This clan doesn\'t exist!',
            ],
            403
        );
    }

    $clanUsersCount = DB::connection('#hidden')
        ->table('#hidden')
        ->where('party', $clanName)
        ->get();

    $kothWins = DB::connection('#hidden')
        ->table('koth_wins')
        ->where('clan_name', $clanName)
        ->get();

    $clanKillsData = DB::connection('#hidden')
        ->table('#hidden')
        ->where('name', $clanName)
        ->first(['kills']);

    $coreUser = DB::connection('#hidden')
        ->table('#hidden')
        ->where('uuid', $clanData->leader)
        ->first(['user']);

    $clanKills = $clanKillsData ? $clanKillsData->kills : 0;

    $clanComments = DB::connection('#hidden')
        ->table('magmacore_parties_comments')
        ->where('clan_name', $clanName)
        ->get();
@endphp

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Информация за клан {{ $clanName }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Начало</a></li>
                        <li class="breadcrumb-item"><a href="https://v-devs.eu/softwares/magmacraft/profiles/{{$authData->auth_user}}/clanlist">Кланове</a></li>
                        <li class="breadcrumb-item active">{{$clanName}}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Клан : {{ $clanName }}</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                        <div class="row">
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Членове</span>
                                        <span
                                            class="info-box-number text-center text-muted mb-0">{{ $clanUsersCount->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Koth победи</span>
                                        <span
                                            class="info-box-number text-center text-muted mb-0">{{ $kothWins->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Общ брой убийства</span>
                                        <span
                                            class="info-box-number text-center text-muted mb-0">{{ $clanKills }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h4>Коментари публиквани от потребители за клан {{ $clanName }}</h4>
                                @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                                @forelse($clanComments as $clanComment)
                                    <div class="post">
                                        <div class="user-block">
                                            <img class="img-circle img-bordered-sm"
                                                src="https://minepic.org/avatar/{{ $clanComment->comment_user }}"
                                                alt="user image">
                                            <span class="username">
                                                <a
                                                    href="https://v-devs.eu/softwares/magmacraft/profiles/{{ $clanComment->comment_user }}/view?asGuest=1">{{ $clanComment->comment_user }}</a>
                                            </span>
                                            <span class="description">{{ $clanComment->comment_date }}</span>
                                        </div>
                                        <!-- /.user-block -->
                                        <p>
                                            {{ $clanComment->comment_banned ? 'Този коментар е изтрит от модератор, тъй като нарушава общите условия на V-DEVS или тези на MagmaCraft' : $clanComment->comment_content }}
                                        </p>

                                    </div>
                                @empty
                                    <p>Все още няма коментари, бъдете първият който коментира клана и имате шанс да
                                        спечелите 3 tokens</p>
                                @endforelse
                                <form
                                    action="{{ route('submit.comment', ['name' => $pinType->username, 'clanname' => $clanName]) }}"
                                    method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="exampleFormControlTextarea1" class="form-label">Публикувайте
                                            коментар към този клан</label>
                                            <small>* Вашият username ще бъде видим във тази секция, имайте предвид, че
                                              коментар нарушаващ общите условия на сървъра би могъл да се счита за
                                              основание да бъдете потенциално наказан !</small>
                                        <textarea class="form-control" id="comment_content" name="comment_content" rows="3"></textarea>
                                        <button type="submit" class="btn btn-sm btn-warning">Публикувай</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                        <h3 class="text-primary"><i class="fas fa-paint-brush"></i> {{ $clanName }}</h3>
                        <p class="text-muted">{{ $clanData->motd ? $clanData->motd : 'Този клан няма MOTD' }}</p>
                        <br>
                        <div class="text-muted">
                            <p class="text-sm">Лидер
                                <b class="d-block">{{ $coreUser->user }}</b>
                            </p>
                        </div>

                        <div class="text-center mt-5 mb-3">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#reportPlayer">
                                Докладвай клан
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="reportPlayer" tabindex="-1" aria-labelledby="reportPlayer"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="modalTitle">Докладване на клан
                                                {{ $clanName }}</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                          <form id="reportForm" action="{{ route('submit.report', ['name' => $pinType->username, 'clanname' => $clanName, 'reason' => '__REPORT_REASON__']) }}" method="post">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="exampleFormControlTextarea1" class="form-label">Докладване на клан</label>
                                                <textarea class="form-control" id="report_content" name="report_reason" rows="3"></textarea>
                                                <button type="submit" class="btn btn-sm btn-warning">Изпращане</button>
                                            </div>
                                        </form>
                                        
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                const reportForm = document.getElementById('reportForm');
                                                const reportContent = document.getElementById('report_content');
                                        
                                                reportForm.addEventListener('submit', function (event) {
                                                    event.preventDefault();
                                        
 
                                                    const reportReason = reportContent.value;
                                        

                                                    const actionUrl = reportForm.getAttribute('action');
                                                    const modifiedAction = actionUrl.replace('__REPORT_REASON__', encodeURIComponent(reportReason));
                                                    reportForm.setAttribute('action', modifiedAction);
                                        

                                                    reportForm.submit();
                                                });
                                            });
                                        </script>
                                        


                                        </div>
                                    
                                    


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Членове на клан : {{ $clanName }}</h3>
    
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
                @foreach($clanUsersCount as $clanMember)
                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                    <div class="card bg-light d-flex flex-fill">
                      <div class="card-header text-muted border-bottom-0">
                        Член на клан {{$clanMember->party}}
                      </div>
                      <div class="card-body pt-0">
                        <div class="row">
                          <div class="col-7">
                            <h2 class="lead"><b>{{$clanMember->name}}</b></h2>
                            <p class="text-muted text-sm"><b>За {{$clanMember->name}}: </b> {{$clanMember->name}} е член на клан {{$clanMember->party}}  като последното му отношение към клана е било {{ date('Y-m-d H:i:s', $clanMember->timestamp) }}</p>
                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                @if($clanMember->rank == 20)
                                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Роля: Лидер</li>
                                @elseif($clanMember->rank == 10)
                                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Роля: Модератор</li>
                                @elseif($clanMember->rank == 5)
                                <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span> Роля: Потребител</li>
                                @endif
                            </ul>
                          </div>
                          <div class="col-5 text-center">
                            <img src="https://minepic.org/avatar/{{$clanMember->name}}" alt="user-avatar" class="img-circle img-fluid">
                          </div>
                        </div>
                      </div>
                      <div class="card-footer">
                        <div class="text-right">
                          <a href="#" class="btn btn-sm bg-teal">
                            <i class="fas fa-comments"></i>
                          </a>
                          @if( $coreUser->user  == $pinType->username)
                            @if(!$clanMember->name == $pinType->username)
                            <a href="#" class="btn btn-sm btn-primary">
                                <i class="fas fa-user"></i> Премахни потребител
                              </a>
                              @endif
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
            </div>
            
            <!-- /.card-body -->
            {{-- <div class="card-footer">
              Footer
            </div> --}}
            <!-- /.card-footer-->
          </div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Koth победи на клан {{$clanName}}</h3>
              @php
              $kothWins = DB::connection('#hidden')->table('koth_wins')->where('clan_name',$clanName)->get();
              @endphp
              <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Clan</th>
                    <th>Дата</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($kothWins as $kothWin)
                    <tr>
                        <td>{{$kothWin->id}}</td>
                        <td>{{$kothWin->clan_name}}</td>
                        <td>{{$kothWin->date}}</td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Наказания на клан {{$clanName}}</h3>
              @php
              $clanPunishments = DB::connection('#hidden')->table('clan_punishments')->where('clan_name',$clanName)->get();
              @endphp
              <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Clan</th>
                    <th>Дата</th>
                    <th>Причина</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach($clanPunishments as $punishment)
                    <tr>
                        <td>{{$punishment->id}}</td>
                        <td>{{$punishment->clan_name}}</td>
                        <td>{{$punishment->punish_date}}</td>
                        <td>{{$punishment->punish_reason}}</td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
        <!-- /.card-body -->
</div>
<!-- /.card -->

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
