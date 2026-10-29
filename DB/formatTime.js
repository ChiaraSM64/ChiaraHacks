// "hh:mm:ss.ms" -> "1h 23m 45s" / "12m 34s 560ms"
function formatTime(time) {
  const [hms, msStr = "0"] = time.split(".");
  const [h, m, s] = hms.split(":").map(Number);
  const ms = Number(msStr.padEnd(3, "0"));
  const parts = [];
  if (h) parts.push(`${h}h`);
  if (h || m) parts.push(`${m}m`);
  parts.push(`${s}s`);
  if (ms) parts.push(`${ms}ms`);
  return parts.join(" ");
}
module.exports = formatTime;
