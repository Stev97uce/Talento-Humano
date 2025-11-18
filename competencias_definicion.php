<?php
/**
 * Definición de competencias específicas por cargo
 * Si un cargo no tiene competencias definidas, se usan las genéricas por defecto
 */

function obtenerCompetenciasPorCargo($nombre_cargo) {
    
    // Competencias genéricas por defecto
    $competencias_genericas = [
        [
            'nombre' => 'Pensamiento estratégico',
            'descripcion' => 'Demuestra capacidad de análisis y planificación estratégica en sus funciones.'
        ],
        [
            'nombre' => 'Comunicación efectiva',
            'descripcion' => 'Transmite información de manera clara, precisa y oportuna.'
        ],
        [
            'nombre' => 'Manejo de conflictos',
            'descripcion' => 'Gestiona y resuelve conflictos de manera constructiva y profesional.'
        ],
        [
            'nombre' => 'Liderazgo',
            'descripcion' => 'Demuestra capacidad de liderazgo y orientación de equipos.'
        ],
        [
            'nombre' => 'Trabajo en equipo',
            'descripcion' => 'Colabora eficientemente y mantiene relaciones interpersonales positivas.'
        ],
        [
            'nombre' => 'Adaptabilidad',
            'descripcion' => 'Se adapta fácilmente a cambios y nuevas situaciones.'
        ],
        [
            'nombre' => 'Orientación al cliente',
            'descripcion' => 'Enfoca sus esfuerzos en satisfacer las necesidades del cliente interno y externo.'
        ]
    ];
    
    // Definir competencias específicas por cargo
    $competencias_por_cargo = [
        'Auxiliar de Servicios Generales' => [
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad'
            ],
            [
                'nombre' => 'Servicio al cliente',
                'descripcion' => 'Implica un deseo de ayudar o servir a los clientes, de comprender y satisfacer sus necesidades, aun aquéllas no expresadas. Implica esforzarse por conocer y resolver los problemas del cliente, tanto del cliente final a quien van dirigidos los esfuerzos de la empresa como los clientes de los propios clientes y todos aquellos que cooperen en la relación empresa-cliente, como el personal ajeno a la organización. No se trata tanto de una conducta concreta frente a un cliente real como de una actitud permanente de contar con las necesidades del cliente para incorporar este conocimiento a la forma específica de planificar la actividad.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica también una insistencia en que las responsabilidades y funciones asignadas estén claramente asignadas.'
            ],
            [
                'nombre' => 'Dinamismo – energía',
                'descripcion' => 'Se trata de la habilidad para trabajar duro en situaciones cambiantes o alternativas, con interlocutores muy diversos, que cambian en cortos espacios de tiempo, en jornadas de trabajo prolongadas sin que por esto se vea afectado su nivel de actividad.'
            ]
        ],
        
        'Coordinador de Bodega' => [
            [
                'nombre' => 'Pensamiento estratégico',
                'descripcion' => 'Es la habilidad para comprender rápidamente los cambios del entorno, las oportunidades del mercado, las amenazas competitivas y las fortalezas y debilidades de su propia organización a la hora de identificar la mejor respuesta estratégica. Capacidad para detectar nuevas oportunidades de negocio y realizar alianzas estratégicas.'
            ],
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Es la capacidad de idear la solución a un conflicto, atendiendo sus necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la capacidad de idear soluciones para resolver diferencias de ideas u opiniones 2 o más partes, evitando la manipulación y la parcialidad de los intereses personales.'
            ],
            [
                'nombre' => 'Análisis Numérico',
                'descripcion' => 'Capacidad para analizar, organizar y presentar datos numéricos de manera exacta. Competencia propia de quienes tienen que desempeñar cargos relacionados con el área contable y/o financiera de una organización.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica también una insistencia en que las responsabilidades y funciones asignadas estén claramente asignadas.'
            ]
        ],
                
        'Auxiliar de servicios generales administrativos' => [
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad.'
            ],
            [
                'nombre' => 'Servicio al cliente',
                'descripcion' => 'Implica un deseo de ayudar o servir a los clientes, de comprender y satisfacer sus necesidades, aun aquéllas no expresadas. Implica esforzarse por conocer y resolver los problemas del cliente, tanto del cliente final a quien van dirigidos los esfuerzos de la empresa como los clientes de los propios clientes y todos aquellos que cooperen en la relación empresa-cliente, como el personal ajeno a la organización. No se trata tanto de una conducta concreta frente a un cliente real como de una actitud permanente de contar con las necesidades del cliente para incorporar este conocimiento a la forma específica de planificar la actividad.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica también una insistencia en que las responsabilidades y funciones asignadas estén claramente asignadas.'
            ],
            [
                'nombre' => 'Confidencialidad y Ética Profesional',
                'descripcion' => 'Manejo adecuado de información sensible y cumplimiento de las normas éticas de la organización.'
            ],
            [
                'nombre' => 'Dinamismo – energía',
                'descripcion' => 'Se trata de la habilidad para trabajar duro en situaciones cambiantes o alternativas, con interlocutores muy diversos, que cambian en cortos espacios de tiempo, en jornadas de trabajo prolongadas sin que por esto se vea afectado su nivel de actividad.'
            ]
        ],
        
        'Pasante TICS' => [
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad'
            ],
            [
                'nombre' => 'Orientación al cliente interno y externo ',
                'descripcion' => 'Demostrar sensibilidad por las necesidades o exigencias que un conjunto de clientes potenciales externos o internos pueden requerir en el presente o en el futuro. No se trata tanto de una conducta concreta frente a un cliente real como de una actitud permanente de contar con las necesidades del cliente para incorporar este conocimiento a la forma específica de planear a la actividad. Se la diferencia de "atención al cliente", que tiene más relación con atender las necesidades de un cliente real y concreto en la interacción. Conceder la más alta calidad a la satisfacción del cliente. Escuchar al cliente. Generar Soluciones para satisfacer las necesidades de los clientes. Estar comprometido con la calidad, esforzándose por una mejora continua.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica también una insistencia en que las responsabilidades y funciones asignadas estén claramente asignadas.'
            ],
            [
                'nombre' => 'Aprendizaje Continuo',
                'descripcion' => 'Es la habilidad para buscar y compartir información útil para la resolución de situa­ciones de negocios, utilizando todo el potencial de la empresa (o corporación, según co­rresponda). Incluye la capacidad de capitalizar la experiencia de otros y la propia, propagando el know how adquirido en foros locales o internacionales.'
            ],
            [
                'nombre' => 'Dinamismo - energía',
                'descripcion' => 'Se trata de la habilidad para trabajar duro en situaciones cambiantes o alternativas, con interlocutores muy diversos, que cambian en cortos espacios de tiempo, en jornadas de trabajo prolongadas sin que por esto se vea afectado su nivel de actividad.'
            ]
        ]
    ];
    
    // Normalizar el nombre del cargo para búsqueda (sin distinguir mayúsculas/minúsculas)
    $nombre_cargo_normalizado = mb_strtolower(trim($nombre_cargo));
    
    // Buscar competencias específicas del cargo
    foreach ($competencias_por_cargo as $cargo => $competencias) {
        if (mb_strtolower($cargo) === $nombre_cargo_normalizado) {
            return [
                'competencias' => $competencias,
                'es_especifico' => true,
                'cargo' => $cargo
            ];
        }
    }
    
    // Si no hay competencias específicas, devolver las genéricas
    return [
        'competencias' => $competencias_genericas,
        'es_especifico' => false,
        'cargo' => $nombre_cargo
    ];
}
?>