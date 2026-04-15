<?php
header('Content-Type: text/plain');
header('Cache-Control: no-cache');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Method not allowed");
}

$input = json_decode(file_get_contents('php://input'), true);
$topic = $input['topic'] ?? '';
$billTypeInput = $input['billType'] ?? 'ordinance';
$typeLabel = ($billTypeInput === 'resolution') ? 'RES' : 'ORD';
$today = date('Ymd'); 
$random = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
$trackingNo = "{$typeLabel}-{$today}-{$random}";

if (empty($topic)) {
    http_response_code(400);
    echo "Error: Topic is required";
    exit;
}

$instruction = "
Draft an LGU {$billTypeInput} for the Republic of the Philippines. 
Topic: \"{$topic}\"

Formatting requirements:
- Start with standard 2026 header (Local Government Unit context) and 9-point section structure.
- Tone: Formal, precise legal English.

CRITICAL: After the end of the legislative text, add exactly \"---METADATA---\" followed by these fields. 

1. Title: (Extract a concise 5-10 word topic title based on your draft)
2. ShortTitle: (Same as the concise title above)
3. Summary: (Provide a 1-2 sentence high-level summary of the purpose of this {$billTypeInput})
4. TrackingNo: {$trackingNo}
5. WhereasDescription: (Full text of all WHEREAS clauses combined)
6. Committee: 
7. Type: " . ucfirst($billTypeInput) . "
8. PrincipalAuthor: 
9. DeadlineOrHearingDate: 
10. Sec1_Desc: (Full text of Section 1)
11. Sec2_Desc: (Full text of Section 2)
12. Sec3_Desc: (Full text of Section 3)
13. Sec4_Desc: (Full text of Section 4)
14. Sec5_Desc: (Full text of Section 5)
15. Sec6_Desc: (Full text of Section 6)
16. Sec7_Desc: (Full text of Section 7)
17. Sec8_Desc: (Full text of Section 8)
18. Sec9_Desc: (Full text of Section 9)
";

$apiUrl = "https://open-ai-hosting-free-production.up.railway.app/api/assistant/gemini-bill-generator";
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); 
curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) {
    echo $data;
    if (ob_get_level() > 0) ob_flush();
    flush();
    return strlen($data);
});
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $instruction]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_exec($ch);
curl_close($ch);