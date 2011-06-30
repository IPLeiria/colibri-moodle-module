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
$string['startdate'] = 'Início da sessão';
$string['startdate_help'] = 'Data e hora do inicio da sessão';
$string['enddate'] = 'Fim da sessão';
$string['enddate_help'] = 'Data e hora do fim da sessão';
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

$string['DATABASE_INSERT_FAILED'] = 'Falhou a inserção na base de dados';
$string['DATABASE_UPDATE_FAILED'] = 'Falhou a actualização na base de dados';
$string['DATABASE_DELETE_FAILED'] = 'Falhou a eliminação na base de dados';

$string['sessionscheduletostart'] = ' (agendada)';
$string['sessionscheduletostart_title'] = 'Sessão agendada para {$a[\'weekday\']}, {$a[\'mday\']} de {$a[\'month\']} de {$a[\'year\']} às {$a[\'hours\']}:{$a[\'minutes\']}';
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



//Capabilities
$string['colibri:configureplugin'] = 'Configurar as definições do módulo Colibri';


// library strings
$string['webServiceLinkFailed'] = 'Não foi possível estabelecer a ligação ao web service';
$string['unableToConnectToTheServer'] = 'Não foi possível estabelecer ligação ao servidor remoto';
$string['unableToCreateSoapClientInstance'] = 'Não foi possível criar uma instância cliente SOAP para o módulo';
$string['remoteMethodExecutionFailed'] = 'Não foi possível executar o metodo SOAP remoto';
$string['invalidMethodResponse'] = 'Resposta inválida ou inesperada';

$string['getSoapTypesFailed'] = 'Não foi possível obter a lista de tipos SOAP do servidor Colibri';
$string['getSoapFunctionsFailed'] = 'Não foi possível obter a lista de métodos SOAP do servidor Colibri';
$string['getColibriTimeFailed'] = 'Não foi possível obter a data do servidor Colibri';

$string['checkAccessFailed'] = 'Não foi possível validar as credenciais no servidor Colibri';

$string['invalidName'] = 'O nome da sessão é inválido';
$string['invalidStartDate'] = 'A data de inicio da sessão é inválida';
$string['invalidEndDate'] = 'A data de fim da sessão é inválida';
$string['invalidNumberOfParticipants'] = 'O número de participantes é inválido (a sessão deve ter participantes!)';
$string['createSessionFailed'] = 'Não foi possível criar a sessão no servidor Colibri';
$string['modifySessionFailed'] = 'Não foi possível modificar a sessão {$a} no servidor Colibri';
$string['removeSessionFailed'] = 'Não foi possível remover a sessão {$a} do servidor Colibri';
$string['getSessionInfoFailed'] = 'Não foi possível obter informações sobre a sessão no servidor Colibri';
$string['getSessionsInfoFailed'] = 'Não foi possível obter informações sobre as sessões no servidor Colibri';

$string['errorReturned'] = 'Ocorreu um erro ({$a})';
$string['GENERIC_ERROR'] = 'Erro: {$a}';
$string['SOAP_INSTANCE_ERROR'] = 'Erro na instância do cliente SOAP';
$string['SOAP_FAULT'] = 'Erro de SOAP';
$string['EXCEPTION'] = 'Ocorreu uma excepção';
$string['INVALID_START_DATE'] = 'Data de inicio inválida';
$string['INVALID_END_DATE'] = 'Data de fim inválida';
$string['INVALID_NUMBER_OF_PARTICIPANTS'] = 'Número de participantes inválido';
$string['ERROR_ON_METHOD_RESPONSE'] = 'Erro na resposta do método remoto';
$string['INVALID_ACCESS_CREDENTIALS'] = 'As credenciais são inválidas';
$string['MISSING_ACCESS_CREDENTIALS'] = 'As credenciais não foram enviadas no pedido';
$string['UNKOWN_ERROR'] = 'Erro desconhecido: {$a}';
$string['COULD_NOT_CREATE_SESSION'] = 'Não foi possível criar a sessão';
$string['MISSING_SESSION_NAME'] = 'O nome da sessão não se encontra definido';
$string['SESSION_START_TIME_GREATER_THAN_ENDTIME'] = 'A data de inicio é superior ou igual à data de fim';
$string['COULD_NOT_GET_SESSION_INFO'] = 'Não foi possivel obter informações sobre a sessão';
$string['INSUFFICIENT_PERMISSIONS'] = 'Permissões insuficientes';
$string['COULD_NOT_MODIFY_SESSION'] = 'Não foi possível modificar a sessão';
$string['COULD_NOT_REMOVE_SESSION'] = 'Não foi possível remover a sessão';
$string['INVALID_NAME'] = 'Nome de sessão inválido';

// settings menu
$string['colibri'] = 'Colibri';
$string['configuration'] = 'Configuração';
// configuration page
$string['colibriConfiguration'] = 'Configuração do Colibri';
$string['insuficientPermissionsToConfigureTheColibriPlugin'] = 'Não tem permissões suficientes para configurar o módulo Colibri';
$string['colibriSettingsSaved'] = 'Definições guardadas com sucesso';
$string['colibriErrorSavingSettings'] = 'Ocorreu um erro ao guardar as definições ({$a})';
// configuration form
$string['about'] = 'Sobre';
$string['usingPluginVersion'] = 'Versão do módulo local: {$a}';
$string['generalSettings'] = 'Definições Gerais';
$string['colibriWsdlUrl'] = 'Endereço do WSDL';
$string['colibriEmptyWsdlURL'] = 'O endereço para o WSDL tem de ser definido';
$string['colibriWsdlUrl_help'] = 'O endereço WSDL é a URL com o documento que descreve os serviços disponíveis no web service Colibri';
$string['colibriDirectAccessUrl'] = 'URL de acesso direto';
$string['emptyColibriDirectAccessUrl'] = 'A URL the acesso direto não pode ser nula';
$string['colibriDirectAccessUrl_help'] = 'A URL base que permite o acesso direto a uma sessão';
$string['instanceSettings'] = 'Definições da Instância';
$string['colibriInstallationIdentifier'] = 'Identificador da Instalação';
$string['colibriEmptyInstallationIdentifier'] = 'O identificador da instalação tem de ser definido';
$string['colibriInstallationIdentifier_help'] = 'O identificador da instalação é o idenficador único fornecido pelos serviços Colibri para utilização dos web services';
$string['colibriInstallationPassword'] = 'Chave da Instalação';
$string['colibriEmptyInstallationPassword'] = 'A chave da instalação tem de ser definida';
$string['colibriInstallationPassword_help'] = 'A chave da instalação é uma senha fornecida pelos serviços Colibri e que valida o identificador da instalação';
$string['sessioninformationupdatemethod'] = 'Informação sobre as Sessões';
$string['sessioninformationupdatemethod_help'] = 'Método que deve ser utilizado para obter informação sobre cada uma das sessões. As opções são \'online\', para obter as informações diretamente a partir do serviço Colibri, \'cron\' para ober as informações a partir da base de dados local e sincronizar a informação entre serviços utilizando o cron, ou \'local\' para utilizar apenas as informações que constam na base de dados local.';
$string['live'] = 'online';
$string['cron'] = 'cron';
$string['local'] = 'local';
$string['save'] = 'Guardar';
$string['colibriInvalidWsdlUrl'] = 'Não é possível estabelecer uma ligação com o web service. Por favor, verifique a URL e tente novamente.';


$string['sessionnames'] = 'Nome da sessão';
$string['sessionstart'] = 'Inicio da sessão';
$string['sessionduration'] = 'Duração da sessão';
$string['numberofparticipants'] = 'Numero de participantes';
$string['startdatetime'] = '{$a[\'weekday\']}, {$a[\'month\']} {$a[\'mday\']}, {$a[\'year\']} às {$a[\'hours\']}:{$a[\'minutes\']}';
$string['durationtime'] = '{$a[\'hours\']} hora(s) e {$a[\'mins\']} minuto(s)';
