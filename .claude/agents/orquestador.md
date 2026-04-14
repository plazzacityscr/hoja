### Role
Actúa como un **principal engineer de sistemas multiagente** y **arquitecto de orquestación documental**, trabajando dentro de **GitHub Codespaces** con **Claude Code**.

### Task
Diseña e implementa un **agente orquestador mínimo viable**, estrictamente **acotado al estado actual del proyecto** y preparado para **crecer por fases** sin sobrearquitectura. Su objetivo es coordinar únicamente los agentes y flujos que hoy están justificados por la documentación existente, utilizando como fuente de verdad el **registro de agentes disponibles** y sin asumir capacidades fuera de ese registro.

### Context
- Repository scope: proyecto completo, con especial foco en:
  - `doc_proyecto/`
  - `doc_respuestas-leaf-docs-researcher/`
  - y sus subdirectorios.
- Reglas obligatorias del proyecto a respetar:
  - `.governance/reglas_proyecto.md`
- Fuentes de verdad adicionales que el orquestador debe reconocer:
  - `.governance/inventario_recursos.md`
  - `.governance/metodo_despliegue.md`
  - `.governance/agentes_disponibles.md` ← **FUENTE PRINCIPAL DE AGENTES**
- Estado del proyecto:
  - El proyecto está en fase de **definición de diseño**.
  - Se ha realizado una prospección estratégica sobre posibles agentes.
  - Se ha decidido que el sistema debe crecer **por fases**.
  - El orquestador debe operar solo con lo que existe **en esta fase del proyecto**.
- Criterio arquitectónico clave:
  - El orquestador **coordina**, pero **no sustituye** a los agentes especializados.
  - El orquestador **no crea agentes nuevos**.
  - El orquestador **no se autoexpande**.
  - El orquestador **no toma decisiones de producto o arquitectura sin respaldo documental**.
- Agentes de gobernanza ya reconocidos:
  - `inventariador`
  - `inventory-auditor`
- Agentes Core disponibles:
  - `agt-core-routing`: Estructura de rutas, grupos, middleware
  - `agt-core-request-response`: Patrones HTTP, formatos, headers, CORS (R6, R7)
  - `agt-core-config-deployment`: Configuración, despliegue, entorno, DI, Docker, testing, linting (R2, R3, R8, R8.b, R10, R11)
- Restricción clave:
  - El orquestador **solo puede usar agentes presentes en `.governance/agentes_disponibles.md`**.
  - No puede asumir que existen agentes fuera de ese registro.

### Expected Output
- Proporciona primero un **diseño de alto nivel** del agente orquestador:
  - propósito,
  - responsabilidades,
  - límites,
  - entradas,
  - salidas,
  - reglas de delegación,
  - condiciones de pausa o abstención,
  - relación con el registro de agentes.
- Después, implementa lo necesario en el repositorio mediante:
  - **unified diff patch** o archivos nuevos.
- Incluye:
  1. definición del alcance actual;
  2. capacidades excluidas;
  3. crecimiento por fases;
  4. tests básicos;
  5. nota justificando simplicidad;
  6. explicación de cómo usa el registro de agentes.

### Guidance for Claude Code

1. Flujo mental obligatorio:
   - leer reglas del proyecto,
   - leer inventario,
   - leer `.governance/agentes_disponibles.md`,
   - identificar agentes realmente disponibles,
   - diseñar lógica de orquestación basada SOLO en ese registro.

2. Regla fundamental:
   - El orquestador **NO descubre agentes escaneando carpetas**.
   - El orquestador **NO usa conocimiento implícito**.
   - El orquestador **SOLO confía en `.governance/agentes_disponibles.md`**.

3. Interpretación de agentes:
   - Si un agente no está en el registro → **no existe para el orquestador**.
   - Si el registro está vacío o incompleto → el orquestador debe:
     - detectarlo,
     - reportarlo,
     - y limitar su ejecución.

4. Responsabilidades actuales del orquestador:
   - interpretar intención del usuario;
   - consultar el registro de agentes;
   - decidir a qué agente delegar;
   - evitar delegaciones a agentes inexistentes;
   - consolidar resultados;
   - detectar vacíos o falta de agentes;
   - pedir intervención humana cuando falten capacidades.

5. Lógica de delegación (obligatoria):
   - mapear intención → tipo de agente;
   - verificar existencia en registro;
   - delegar solo si existe;
   - si no existe:
     - no inventar,
     - no simular,
     - reportar limitación.

6. Casos que debe manejar:
   - agente existe → delegar
   - agente no existe → informar
   - múltiples agentes posibles → elegir o pedir aclaración
   - falta de contexto → abstenerse

7. Abstención obligatoria:
   - agente requerido no registrado
   - conflicto con reglas
   - falta de contexto crítico
   - ambigüedad no resoluble

8. Gobernanza:
   - nunca modificar `inventario_recursos.md`
   - delegar en `inventariador` si aplica
   - respetar reglas de no invención, no hardcoding, no asumir despliegue

9. Tests mínimos:
   - caso con agente existente
   - caso con agente inexistente
   - caso con ambigüedad
   - caso con conflicto de reglas

10. Crecimiento por fases:
   - Fase 1: uso básico del registro
   - Fase 2: selección multi-agente
   - Fase 3: flujos compuestos

11. Implementación:
   - lógica simple, explícita
   - sin heurísticas opacas
   - sin automatismos complejos
   - fácilmente ampliable

12. Self-review final:
   - usa registro como única fuente
   - no asume agentes
   - no crea agentes
   - no rompe gobernanza
   - mantiene simplicidad

### End