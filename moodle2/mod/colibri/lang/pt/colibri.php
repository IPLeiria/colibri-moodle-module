<?php
/**
 * Strings for the 'mod_colibri' component, portuguese
 *
 * @package    	Colibri
 * @subpackage 	mod_colibri
 * @author 	Cláudio Esperança <claudio.esperanca@ipleiria.pt> - {@link http://ued.ipleiria.pt | Learning Distance Unit }, Polytechnic Institute of Leiria
 * @license	{@link http://www.gnu.org/copyleft/gpl.html |  GNU GPL v3 or later}
 *
 */

$string['colibri'] = 'Colibri';
$string['name'] = $string['colibri'];
$string['mod_colibri'] = $string['colibri'];
$string['modulename'] = 'Sessão Colibri';
$string['modulenameplural'] = 'Sessões Colibri';
$string['pluginadministration'] = 'Administração do Colibri';


//Capabilities
$string['colibri:managesession'] = 'Gerir sessões Colibri';
$string['colibri:attendsession'] = 'Assistir a sessões Colibri';

//Form
$string['sessionname'] = 'Nome da sessão';
$string['sessionname_help'] = 'O nome da sessão e do recurso';
$string['maximumsessionnamechars'] = 'O nome da sessão não deve ser tão longo';
$string['introeditor'] = 'Descrição da actividade';
$string['introeditor_help'] = 'O texto a ser apresentado aos utilizadores antes de entrarem na sessão Colibri';
$string['startdatetime'] = 'Início da sessão';
$string['startdatetime_help'] = 'Data e hora do inicio da sessão';
$string['enddatetime'] = 'Fim da sessão';
$string['enddatetime_help'] = 'Data e hora do fim da sessão';
$string['duration'] = 'Duração da sessão';
$string['duration_help'] = 'Tempo que a sessão irá demorar';
$string['sessionpin'] = 'PIN da sessão';
$string['sessionpin_help'] = 'O PIN da sessão é o código que deve ser introduzido pelos participantes para aceder à sessão. Deve ser composto por 4 dígitos.';
$string['invalidsessionpin'] = 'PIN de sessão inválido';
$string['moderationpin'] = 'PIN de moderação';
$string['moderationpin_help'] = 'O PIN de morderação é o código que deve ser especificado pelos moderadores para assumirem o controle da sessão. Deve ser composto por 4 dígitos.';
$string['invalidmoderationpin'] = 'PIN de moderação inválido';
$string['publicsession'] = 'Sessão pública';
$string['publicsession_help'] = 'Esta sessão é pública? Se sim, então esta sessão será apresentada na lista de sessões públicas no site do Colibri';
$string['authorizedsessionusers_help'] = 'Selecione os utilizadores com acesso explicito à sessão. Os seus lugares serão reservados e não poderão ser ocupados por outros participantes';

$string['sessionusers'] = 'Utilizadores da sessão';
$string['maxsessionusers'] = 'Lugares reservados';
$string['maxsessionusers_help'] = 'O número máximo de utilizadores permitidos na sessão';
$string['maximumsessionusersnumber'] = 'O número de lugares reservados é demasiado grande';
$string['selectusers'] = 'Selecionar participantes';
$string['potencialguests'] = 'Potenciais convidados';
$string['reservedseats'] = 'Lugares reservados';
$string['reserveseat'] = 'Reservar lugar(es)';
$string['reserveseatfortheusers'] = 'Reservar lugar(es) para o(s) utilizador(es) selecionado(s)';
$string['clearseat'] = 'Libertar lugar(es)';
$string['clearseatoftheusers'] = 'Libertar lugar(es) do(s) utilizador(es) selecionado(s)';
$string['users'] = 'Utilizadores';
$string['usersmatching'] = 'Utilizadores encontrados para \'{$a}\'';

//validation
$string['emptysessionname'] = 'Não foi especificado um nome para a sessão';
$string['youcannotcreateasessioninthepast'] = 'Não é possível criar uma sessão no passado';
$string['enddatemustbegreaterthanstartdate'] = 'A data de fim deve ser posterior à data de inicio';
$string['sessionpinmustbeavalidnumber'] = 'O PIN da sessão deve ser um número válido';
$string['moderationpinmustbeavalidnumber'] = 'O PIN de moderação deve ser um número válido';
$string['dontbelikethatandinvitesomeonetothesession'] = 'Não é possível criar sessões sem especificar o número de utilizadores que participarão na sessão';
$string['sessiondurationmustbegreaterthanzero'] = 'A duração deve ser maior do que zero';
$string['sessiondurationmustbeanumber'] = 'A duração deve ser um número';

$string['DATABASE_INSERT_FAILED'] = 'Falhou a inserção na base de dados';
$string['DATABASE_UPDATE_FAILED'] = 'Falhou a actualização na base de dados';
$string['DATABASE_DELETE_FAILED'] = 'Falhou a eliminação na base de dados';

$string['sessionscheduletostart'] = ' (agendada)';
$string['sessionscheduletostart_title'] = 'Sessão agendada para {$a->weekday}, {$a->mday} de {$a->month} de {$a->year} às {$a->hours}:{$a->minutes}';
$string['sessionstartedxparticipantsinsession'] = ' (a decorrer)';
$string['sessionstartedxparticipantsinsession_title'] = 'Em sessão com {$a} participantes';
$string['sessionfinished'] = '';
$string['sessionfinished_title'] = '';

$string['invalidcolibriid'] = 'O idenficador do recurso Colibri é inválido';
$string['unabletogetthesessionstate'] = 'Não foi possível obter o estado da sessão';

$string['thesessionhasended'] = 'A sessão terminou';
$string['unabletoaccessthesession_allseatstaken'] = 'Não é possível aceder à sessão: não existem lugares disponíveis.';
$string['redirectingtothesession'] = 'A redirecionar para a sessão Colibri...';
$string['redirectingtothesessionasmoderator'] = 'A redirecionar para a sessão Colibri. Pode moderar esta sessão utilizando o PIN de moderação {$a} depois de entrar na sessão.';
