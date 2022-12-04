import YAML from "yamljs";
import swagger from 'swagger-ui-express';
import express from "express";
import http from 'http';

const app = express();
const server = http.createServer(app);

server.listen(3000, '0.0.0.0');

const docs = YAML.load('./swagger.yml');
app.use('/', swagger.serve, swagger.setup(docs));
