import { Hono } from "npm:hono";
import { cors } from "npm:hono/cors";
import { logger } from "npm:hono/logger";
import * as kv from "./kv_store.tsx";

const app = new Hono();

// Enable logger
app.use('*', logger(console.log));

// Enable CORS
app.use(
  "/*",
  cors({
    origin: "*",
    allowHeaders: ["Content-Type", "Authorization"],
    allowMethods: ["GET", "POST", "PUT", "DELETE", "OPTIONS"],
    exposeHeaders: ["Content-Length"],
    maxAge: 600,
  }),
);

const BASE_PATH = "/make-server-d33cec4f";

// Health check
app.get(`${BASE_PATH}/health`, (c) => {
  return c.json({ status: "ok" });
});

// --- Courses ---

app.get(`${BASE_PATH}/courses`, async (c) => {
  try {
    const courses = await kv.getByPrefix("course_");
    // Sort by created_at desc if possible, or client side.
    return c.json(courses);
  } catch (err) {
    console.error(err);
    return c.json({ error: err.message }, 500);
  }
});

app.post(`${BASE_PATH}/courses`, async (c) => {
  try {
    const body = await c.req.json();
    const id = Date.now().toString(); // Simple ID generation
    const newCourse = { ...body, id, created_at: new Date().toISOString() };
    await kv.set(`course_${id}`, newCourse);
    return c.json(newCourse, 201);
  } catch (err) {
    console.error(err);
    return c.json({ error: err.message }, 500);
  }
});

// --- Registrations ---

app.get(`${BASE_PATH}/registrations`, async (c) => {
  try {
    const regs = await kv.getByPrefix("reg_");
    return c.json(regs);
  } catch (err) {
    console.error(err);
    return c.json({ error: err.message }, 500);
  }
});

app.post(`${BASE_PATH}/registrations`, async (c) => {
  try {
    const body = await c.req.json();
    const id = Date.now().toString();
    const newReg = { ...body, id, created_at: new Date().toISOString() };
    await kv.set(`reg_${id}`, newReg);
    return c.json(newReg, 201);
  } catch (err) {
    console.error(err);
    return c.json({ error: err.message }, 500);
  }
});

Deno.serve(app.fetch);
