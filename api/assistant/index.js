import express from "express";
import dotenv from "dotenv";
dotenv.config();

const router = express.Router();
const STREAM = true;

router.post("/", async (req, res) => {
  try {
    const { message, context } = req.body || {};
    if (!message) return res.status(400).send("Missing message");
    if (!process.env.OPENAI_API_KEY)
      return res.status(500).send("Missing API key");

    // Build optional page-context string
    let ctxText = "";
    if (context && typeof context === "object") {
      const lines = [];
      if (context.title) lines.push(`Title: ${context.title}`);
      if (context.url) lines.push(`URL: ${context.url}`);
      if (context.metaDesc) lines.push(`Meta: ${context.metaDesc}`);
      if (Array.isArray(context.nav) && context.nav.length)
        lines.push(`Nav: ${context.nav.join(" | ")}`);
      if (Array.isArray(context.sections) && context.sections.length) {
        lines.push("Sections:");
        context.sections.slice(0, 8).forEach((s, i) => {
          lines.push(
            `  ${i + 1}. ${s.name || s.id || "Section"} — ${
              s.text?.slice(0, 300) || ""
            }`
          );
        });
      }
      ctxText = lines.join("\n");
    }

    const messages = [
      {
        role: "system",
        content:
          "You are the official assistant for the Urdaneta City - Legislative Information System website. " +
          "Your scope is strictly: explaining website content and features, the Philippine legislative process, bills, schedules, committees, " +
          "and how citizens can use the portal. Do NOT provide programming/code help unrelated to this site. " +
          "Use plain, neutral, factual language.",
      },
      ...(ctxText
        ? [
            {
              role: "system",
              content: "Page context (client DOM, may be partial):\n" + ctxText,
            },
          ]
        : []),
      { role: "user", content: message },
    ];

    const upstream = await fetch("https://api.openai.com/v1/chat/completions", {
      method: "POST",
      headers: {
        Authorization: `Bearer ${process.env.OPENAI_API_KEY}`,
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        model: "gpt-4o-mini",
        stream: STREAM,
        messages,
        temperature: 0.2,
      }),
    });

    if (!upstream.ok) {
      const errText = await upstream.text().catch(() => "");
      console.error("OpenAI error:", upstream.status, errText);
      return res
        .status(upstream.status || 502)
        .send(errText || "Upstream error");
    }

    // Non-stream (debug fallback)
    if (!STREAM) {
      const data = await upstream.json();
      const text = data?.choices?.[0]?.message?.content || "";
      return res.set("Content-Type", "text/plain; charset=utf-8").send(text);
    }

    // Stream SSE -> plain text chunks to client
    if (!upstream.body || typeof upstream.body.getReader !== "function") {
      // If some runtime wraps the body differently, just return the raw text
      const txt = await upstream.text();
      return res.set("Content-Type", "text/plain; charset=utf-8").send(txt);
    }

    res.setHeader("Content-Type", "text/plain; charset=utf-8");
    res.setHeader("Transfer-Encoding", "chunked");

    const reader = upstream.body.getReader();
    const decoder = new TextDecoder();

    while (true) {
      const { done, value } = await reader.read();
      if (done) break;
      const chunk = decoder.decode(value, { stream: true });

      // Parse SSE lines: data: {...}
      const lines = chunk.split("\n").filter((l) => l.startsWith("data: "));
      for (const line of lines) {
        const jsonStr = line.replace(/^data:\s*/, "");
        if (jsonStr === "[DONE]") continue;
        try {
          const json = JSON.parse(jsonStr);
          const delta = json.choices?.[0]?.delta?.content || "";
          if (delta) res.write(delta);
        } catch {
          // ignore keep-alives/heartbeats
        }
      }
    }

    res.end();
  } catch (err) {
    console.error("Assistant route error:", err);
    res.status(500).send("Server error");
  }
});

export default router;
