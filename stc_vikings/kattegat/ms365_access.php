<?php
/**
 * Microsoft 365 dashboard is limited to Boss and Nausher.
 * stc_user_role: 6 = Boss, 9 = Nausher
 * stc_user_id: 1 = Nausher Khan, 6 = SHAIKH SAFIKUL ISLAM (Boss)
 */
function stc_ms365_user_allowed(): bool {
	$role = (int) ($_SESSION['stc_empl_role'] ?? 0);
	$uid = (int) ($_SESSION['stc_empl_id'] ?? 0);
	return in_array($role, [6, 9], true) || in_array($uid, [1, 6], true);
}
