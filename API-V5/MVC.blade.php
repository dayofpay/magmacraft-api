{{-- This is example usage of the api in production environment --}}

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
          <div class="container-fluid">
              <div class="row mb-2">
                  <div class="col-sm-6">
                      <h1>Разглеждане на профил {{ $userBrowse }}</h1>
                  </div>
                  <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                          <li class="breadcrumb-item"><a href="#">Начална страница</a></li>
                          <li class="breadcrumb-item active">Профил на {{ $userBrowse }}</li>
                      </ol>
                  </div>
              </div>
          </div><!-- /.container-fluid -->
      </section>
      @if (session('kick.success'))
          <div class="alert alert-success">
              {{ session('kick.success') }}
          </div>
      @elseif(session('kick.failure'))
          <div class="alert alert-danger">
              {{ session('kick.failure') }}
          </div>
      @endif
      @if (session('ban.success'))
          <div class="alert alert-success">
              {{ session('ban.success') }}
          </div>
      @elseif(session('ban.failure'))
          <div class="alert alert-danger">
              {{ session('ban.failure') }}
          </div>
      @endif

      @if (session('mute.success'))
          <div class="alert alert-success">
              {{ session('mute.success') }}
          </div>
      @elseif(session('mute.failure'))
          <div class="alert alert-danger">
              {{ session('mute.failure') }}
          </div>
      @endif
      <!-- Main content -->
      <section class="content">
          @if (session('success'))
              <div class="alert alert-success">
                  {{ session('success') }}
              </div>
          @endif
          <div class="container-fluid">
              <div class="row">
                  <div class="col-md-3">

                      <!-- Profile Image -->
                      <div class="card card-primary card-outline">
                          <div class="card-body box-profile">
                              <div class="text-center">
                                  <img class="profile-user-img img-fluid img-circle"
                                      src="https://minepic.org/avatar/{{ $userBrowse }}" alt="User profile picture">
                              </div>

                              <h3 class="profile-username text-center">{{ $userBrowse }}</h3>
                              <p class="text-muted text-center">Потребител на сървъра</p>

                              {{-- PHP VARIABLES --}}
                              @php
                                  $userId = DB::connection('magmaDB')
                                      ->table('container_lhrv')
                                      ->where('name', $userBrowse)
                                      ->value('player_id');
                                  
                                  $killsRecord = DB::connection('magmaDB')
                                      ->table('container_lhrv_default_alltime')
                                      ->where('player_id', $userId)
                                      ->where('stat_id', 1)
                                      ->first();
                                  $kills = $killsRecord ? $killsRecord->stat_value : 'Няма информация';
                                  
                                  $deathsRecord = DB::connection('magmaDB')
                                      ->table('container_lhrv_default_lhrv_alltime')
                                      ->where('player_id', $userId)
                                      ->where('stat_id', 2)
                                      ->first();
                                  $deaths = $deathsRecord ? $deathsRecord->stat_value : 'Няма информация';
                              @endphp

                              <ul class="list-group list-group-unbordered mb-3">
                                  <li class="list-group-item">
                                      <b>Убийства</b> <a class="float-right">{{ $kills }}</a>
                                  </li>
                                  <li class="list-group-item">
                                      <b>Умирания</b> <a class="float-right">{{ $deaths }}</a>
                                  </li>
                                  <li class="list-group-item">
                                      <b>Токени</b> <a class="float-right">{{ $tokenData->tokens }}</a>
                                  </li>
                                  <li class="list-group-item">
                                      <b>Пари</b> <a class="float-right">{{ $essentialsData->money }}$</a>
                                  </li>
                                  <li class="list-group-item">
                                      <b>Последно на линия</b><a
                                          class="float-right">{{ date('Y-m-d H:i:s', $essentialsData->last_seen / 1000) }}</a>
                                  </li>
                              </ul>

                              <a href="#" class="btn btn-primary btn-block" id="followBtn"><b>Последвай</b></a>

                              @if ($authData->is_staff)
                                  <button type="button" class="btn btn-danger btn-block" data-toggle="modal"
                                      data-target="#kickModal">
                                      <i class="fas fa-trash"></i> <b>[STAFF] KICK</b>
                                  </button>
                                  <button type="button" class="btn btn-danger btn-block" data-toggle="modal"
                                      data-target="#banModal">
                                      <i class="fas fa-trash"></i> <b>[STAFF] BAN</b>
                                  </button>
                                  <button type="button" class="btn btn-danger btn-block" data-toggle="modal"
                                      data-target="#muteModal">
                                      <i class="fas fa-trash"></i> <b>[STAFF] MUTE</b>
                                  </button>
                              @endif
                              <script>
                                  document.getElementById('followBtn').addEventListener('click', followEvent);


                                  function followEvent(e) {
                                      alert('На този етап тази функция все още не работи');
                                  }
                              </script>
                          </div>
                          <!-- /.card-body -->
                      </div>
                      <!-- /.card -->

                      <!-- About Me Box -->
                      <div class="card card-primary">
                          <div class="card-header">
                              <h3 class="card-title">Относно {{ $userBrowse }}</h3>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">
                              <strong><i class="fas fa-book mr-1"></i> Дата на регистрация</strong>

                              <p class="text-muted">
                                  {{ date('Y-m-d H:i:s', $authmeData->regdate / 1000) }}
                              </p>

                              <hr>

                              <strong><i class="fas fa-map-marker-alt mr-1"></i> Последна локация</strong>

                              <p class="text-muted">Този потребител не желае да споделя последното си местонахождение
                              </p>

                              <hr>
                              @php
                                  
                                  $userClan = DB::connection('magmaDB')
                                      ->table('vdevscore.clan.data')
                                      ->where('name', $userBrowse)
                                      ->first();
                              @endphp
                              <strong><i class="fas fa-chess-rook mr-1"></i> Клан</strong>

                              @if ($userClan)
                                  <p class="text-muted">{{ $userClan->party }}</p>
                              @else
                                  <p class="text-muted">Потребителя не членува в клан</p>
                              @endif

                          </div>
                          <!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                  </div>
                  <!-- /.col -->
                  <div class="col-md-9">
                      <div class="card">
                          <div class="card-header p-2">
                              <ul class="nav nav-pills">
                                  <li class="nav-item"><a class="nav-link active" href="#activity"
                                          data-toggle="tab">Стена</a></li>
                                  <li class="nav-item"><a class="nav-link" href="#punishments"
                                          data-toggle="tab">Наказания</a></li>
                              </ul>
                          </div><!-- /.card-header -->
                          <div class="card-body">
                              <div class="tab-content">
                                  <div class="active tab-pane" id="activity">

                                      @forelse($profileComments as $comment)
                                          <!-- Post -->
                                          <div class="post clearfix">
                                              <div class="user-block">
                                                  <img class="img-circle img-bordered-sm"
                                                      src="https://minepic.org/avatar/{{ $comment->comment_author }}"
                                                      alt="User Image">
                                                  <span class="username">
                                                      <a
                                                          href="https://v-devs.eu/softwares/magmacraft/profiles/{{ $authData->auth_user }}/users/{{ $comment->comment_author }}">{{ $comment->comment_author }}</a>
                                                      <a href="#" class="float-right btn-tool"><i
                                                              class="fas fa-times"></i></a>
                                                  </span>
                                                  <span class="description">{{ $comment->comment_date }}</span>
                                              </div>
                                              <!-- /.user-block -->
                                              <p>
                                                  {{ $comment->comment_content }}
                                              </p>

                                          </div>
                                          <!-- /.post -->
                                      @empty
                                          <p>Няма коментари все още</p>
                                      @endforelse

                                      <form
                                          action="{{ route('comments.store', ['name' => $pinType->username, 'commentAuthor' => $userBrowse]) }}"
                                          method="post">
                                          @csrf
                                          <div class="mb-3">
                                              <label for="magmacore.services.postcomment"
                                                  class="form-label">Публикувайте
                                                  коментар към този потребител</label>
                                              <small>* Вашият username ще бъде видим във тази секция, имайте предвид, че
                                                  коментар нарушаващ общите условия на сървъра би могъл да се счита за
                                                  основание да бъдете потенциално наказан !</small>
                                              <textarea class="form-control" id="comment_content" name="comment_content" rows="3"></textarea>
                                              <button type="submit" class="btn btn-sm btn-warning">Публикувай</button>
                                          </div>
                                      </form>

                                  </div>


                                  <div class="tab-pane" id="punishments">
                                      <div class="card-body table-responsive p-0">
                                          <table class="table table-hover text-nowrap">
                                              <thead>
                                                  <tr>
                                                      <th>ID</th>
                                                      <th>Наказан потребител</th>
                                                      <th>Наказан от потребител</th>
                                                      <th>Тип наказание</th>
                                                      <th>Причина</th>
                                                      <th>Дата на наказание</th>
                                                      <th>Наказанието изтича на</th>
                                                      <th>Активно</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                                  @forelse($bans as $banData)
                                                      <tr>
                                                          <td>{{ $banData->id }}</td>
                                                          <td>{{ $userBrowse }}</td>
                                                          <td>{{ $banData->banned_by_name }}</td>
                                                          <td>Бан</td>
                                                          <td>{{ $banData->reason }}</td>
                                                          <td>{{ date('Y-m-d H:i:s', $banData->time / 1000) }}</td>
                                                          <td>{{ date('Y-m-d H:i:s', $banData->until / 1000) }}</td>
                                                          <td>{{ $banData->active == 1 ? 'Да' : 'Не' }}</td>
                                                      </tr>
                                                  @empty
                                                      <td>Потребителя не е банван никога</td>
                                                  @endforelse
                                                  @forelse($mutes as $banData)
                                                      <tr>
                                                          <td>{{ $banData->id }}</td>
                                                          <td>{{ $userBrowse }}</td>
                                                          <td>{{ $banData->banned_by_name }}</td>
                                                          <td>Мют</td>
                                                          <td>{{ $banData->reason }}</td>
                                                          <td>{{ date('Y-m-d H:i:s', $banData->time / 1000) }}</td>
                                                          <td>{{ date('Y-m-d H:i:s', $banData->until / 1000) }}</td>
                                                          <td>{{ $banData->active == 1 ? 'Да' : 'Не' }}</td>
                                                      </tr>
                                                  @empty
                                                      <td>Потребителя не е мютван никога</td>
                                                  @endforelse
                                                  @forelse($kicks as $banData)
                                                      <tr>
                                                          <td>{{ $banData->id }}</td>
                                                          <td>{{ $userBrowse }}</td>
                                                          <td>{{ $banData->banned_by_name }}</td>
                                                          <td>Kick</td>
                                                          <td>{{ $banData->reason }}</td>
                                                          <td>{{ date('Y-m-d H:i:s', $banData->time / 1000) }}</td>
                                                          <td>{{ date('Y-m-d H:i:s', $banData->until / 1000) }}</td>
                                                          <td>{{ $banData->active == 1 ? 'Да' : 'Не' }}</td>
                                                      </tr>
                                                  @empty
                                                      <td>Потребителя не е кикван никога</td>
                                                  @endforelse
                                                  @forelse($warns as $banData)
                                                      <tr>
                                                          <td>{{ $banData->id }}</td>
                                                          <td>{{ $userBrowse }}</td>
                                                          <td>{{ $banData->banned_by_name }}</td>
                                                          <td>Предупреждение</td>
                                                          <td>{{ $banData->reason }}</td>
                                                          <td>{{ date('Y-m-d H:i:s', $banData->time / 1000) }}</td>
                                                          <td>{{ date('Y-m-d H:i:s', $banData->until / 1000) }}</td>
                                                          <td>{{ $banData->active == 1 ? 'Да' : 'Не' }}</td>
                                                      </tr>
                                                  @empty
                                                      <td>Потребителя не е предупреждаван никога</td>
                                                  @endforelse
                                              </tbody>
                                          </table>
                                      </div>
                                  </div>
                                  <!-- /.tab-pane -->
                              </div>
                              <!-- /.tab-content -->
                          </div><!-- /.card-body -->
                      </div>
                      <!-- /.card -->
                  </div>
                  <!-- /.col -->
              </div>
              <!-- /.row -->
          </div><!-- /.container-fluid -->


          <div class="modal fade" id="banModal" tabindex="-1" role="dialog" aria-labelledby="banModalLabel"
              aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="banLabel">Банване на {{ $userBrowse }}</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <!-- Form for user ban -->
                          <form id="banForm" method="POST"
                              action="{{ route('staff.ban', ['name' => $pinType->username, 'player' => $userBrowse, '__REASON__', 'duration' => '__DURATION__']) }}">
                              @csrf
                              <div class="form-group">
                                  <label for="banReason">Причина за бан:</label>
                                  <input type="text" class="form-control" id="banReason" name="banreason"
                                      required>
                                  <label for="banDuration">Времетраене: <small>Пример: 1h</small></label>
                                  <input type="text" class="form-control" id="banDuration" name="banDuration"
                                      required>
                              </div>
                              <button type="submit" class="btn btn-danger">Ban</button>
                          </form>
                          <script>
                              document.addEventListener('DOMContentLoaded', function() {
                                  const banForm = document.getElementById('banForm');
                                  const banReasonInput = document.getElementById('banReason');
                                  const banDurationInput = document.getElementById('banDuration');

                                  banForm.addEventListener('submit', function(event) {
                                      event.preventDefault();

                                      const banReason = banReasonInput.value;
                                      const banDuration = banDurationInput.value;
                                      const actionUrl = banForm.getAttribute('action');
                                      const modifiedAction = actionUrl.replace('__REASON__', '[MS-{{ $pinType->username }}]' +
                                          encodeURIComponent(banReason));
                                      const finalAction = modifiedAction.replace('__DURATION__', banDuration);
                                      banForm.setAttribute('action', finalAction);

                                      banForm.submit();
                                  });
                              });
                          </script>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Затвори</button>
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal fade" id="kickModal" tabindex="-1" role="dialog" aria-labelledby="kickModalLabel"
              aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="kickModalLabel">Кик на {{ $userBrowse }}
                          </h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">
                          <!-- Form for kicking the user -->
                          <form id="kickForm" method="POST"
                              action="{{ route('staff.kick', ['name' => $pinType->username, 'player' => $userBrowse, 'reason' => '__REASON__']) }}">
                              @csrf
                              <div class="form-group">
                                  <label for="kickReason">Причина за кика:</label>
                                  <input type="text" class="form-control" id="kickReason" name="reason"
                                      required>
                              </div>
                              <button type="submit" class="btn btn-danger">Кикни</button>
                          </form>

                          <script>
                              document.addEventListener('DOMContentLoaded', function() {
                                  const kickForm = document.getElementById('kickForm');
                                  const kickReasonInput = document.getElementById('kickReason');

                                  kickForm.addEventListener('submit', function(event) {
                                      event.preventDefault();

                                      const kickReason = kickReasonInput.value;
                                      const actionUrl = kickForm.getAttribute('action');
                                      const modifiedAction = actionUrl.replace('__REASON__', '[MS-{{ $pinType->username }}]' +
                                          encodeURIComponent(kickReason));
                                      kickForm.setAttribute('action', modifiedAction);

                                      kickForm.submit();
                                  });
                              });
                          </script>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Затвори</button>
                      </div>
                  </div>
              </div>
          </div>




          <div class="modal fade" id="muteModal" tabindex="-1" role="dialog" aria-labelledby="muteModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="muteModalLabel">Mute на {{ $userBrowse }}
                      </h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <!-- Form for muteing the user -->
                      <form id="muteForm" method="POST"
                          action="{{ route('staff.mute', ['name' => $pinType->username, 'player' => $userBrowse, 'reason' => '__REASON__','duration' => '__DURATION__']) }}">
                          @csrf
                          <div class="form-group">
                              <label for="muteReason">Причина за mute:</label>
                              <input type="text" class="form-control" id="muteReason" name="reason"
                                  required>
                                  <label for="muteDuration">Времетраене: <small>Пример: 1h</small></label>
                                  <input type="text" class="form-control" id="muteDuration" name="muteDuration"
                                      required>
                          </div>
                          <button type="submit" class="btn btn-danger">Mute</button>
                      </form>

                      <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const muteForm = document.getElementById('muteForm');
                            const muteReasonInput = document.getElementById('muteReason');
                            const muteDurationInput = document.getElementById('muteDuration');

                            muteForm.addEventListener('submit', function(event) {
                                event.preventDefault();

                                const muteReason = muteReasonInput.value;
                                const muteDuration = muteDurationInput.value;
                                const actionUrl = muteForm.getAttribute('action');
                                const modifiedAction = actionUrl.replace('__REASON__', '[MS-{{ $pinType->username }}]' +
                                    encodeURIComponent(muteReason));
                                const finalAction = modifiedAction.replace('__DURATION__', muteDuration);
                                muteForm.setAttribute('action', finalAction);

                                muteForm.submit();
                            });
                        });
                    </script>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Затвори</button>
                  </div>
              </div>
          </div>
      </div>
      </section>
      <!-- /.content -->
  </div>
