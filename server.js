import express from "express";
import cors from "cors";
import assistantRouter from "./api/assistant/index.js";

const app = express();
app.use(cors());
app.use(express.json());
app.use("/api/assistant", assistantRouter);

const port = process.env.PORT;
app.listen(port, () =>
  console.log("Assistant API running on http://localhost:" + port)
);
