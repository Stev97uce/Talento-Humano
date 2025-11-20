<?php

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
            ],
        ],
        
        'Asistente administrativo Quito' => [
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Es la capacidad de idear la solución a un conflicto, atendiendo sus necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la capacidad de idear soluciones para resolver diferencias de ideas u opiniones entre 2 o más partes, evitando la manipulación y la parcialidad de los intereses personales.'
            ],
            [
                'nombre' => 'Servicio al cliente',
                'descripcion' => 'Implica un deseo de ayudar o servir a los clientes, de comprender y satisfacer sus necesidades, aun aquellas no expresadas. Implica esforzarse por conocer y resolver los problemas del cliente. No se trata tanto de una conducta concreta frente a un cliente real como de una actitud permanente de contar con las necesidades del cliente para incorporar este conocimiento a la forma específica de planificar la actividad.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica también una insistencia en que las responsabilidades y funciones asignadas estén claramente asignadas.'
            ],
            [
                'nombre' => 'Dinamismo – energía',
                'descripcion' => 'Se trata de la habilidad para trabajar duro en situaciones cambiantes o alternativas, en jornadas prolongadas y con interlocutores diversos, sin que se vea afectado su nivel de actividad.'
            ],
            [
                'nombre' => 'Orientación al cliente interno y externo',
                'descripcion' => 'Demostrar sensibilidad por las necesidades de clientes internos o externos. Conceder la más alta calidad a la satisfacción del cliente, escucharlo y generar soluciones para sus requerimientos. Mantener una actitud permanente de mejora continua.'
            ]
        ],
        
        'Asistente administrativo' => [
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Es la capacidad de idear la solución a un conflicto, atendiendo sus necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la capacidad de idear soluciones para resolver diferencias de ideas u opiniones entre 2 o más partes, evitando la manipulación y la parcialidad de los intereses personales.'
            ],
            [
                'nombre' => 'Servicio al cliente',
                'descripcion' => 'Implica un deseo de ayudar o servir a los clientes, de comprender y satisfacer sus necesidades, aun aquellas no expresadas. Implica esforzarse por conocer y resolver los problemas del cliente. No se trata tanto de una conducta concreta frente a un cliente real como de una actitud permanente de contar con las necesidades del cliente para incorporar este conocimiento a la forma específica de planificar la actividad.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica también una insistencia en que las responsabilidades y funciones asignadas estén claramente asignadas.'
            ],
            [
                'nombre' => 'Dinamismo – energía',
                'descripcion' => 'Se trata de la habilidad para trabajar duro en situaciones cambiantes o alternativas, en jornadas prolongadas y con interlocutores diversos, sin que se vea afectado su nivel de actividad.'
            ],
            [
                'nombre' => 'Orientación al cliente interno y externo',
                'descripcion' => 'Demostrar sensibilidad por las necesidades de clientes internos o externos. Conceder la más alta calidad a la satisfacción del cliente, escucharlo y generar soluciones para sus requerimientos. Mantener una actitud permanente de mejora continua.'
            ]
        ],

        'Auxiliar de bodega' => [
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. Incluye la habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito, así como la capacidad de comunicar por escrito con claridad.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Capacidad de idear soluciones para resolver diferencias de ideas u opiniones entre dos o más partes, atendiendo necesidades y objetivos del negocio, evitando la manipulación y la parcialidad.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Preocupación continua por comprobar y controlar el trabajo y la información, insistiendo en que las responsabilidades asignadas estén claramente definidas.'
            ],
            [
                'nombre' => 'Dinamismo – energía',
                'descripcion' => 'Habilidad para trabajar duro en situaciones cambiantes, con interlocutores diversos, en jornadas prolongadas sin que disminuya su rendimiento.'
            ]
        ],

        'Jefe de cartera y cobranzas' => [
            [
                'nombre' => 'Pensamiento estratégico',
                'descripcion' => 'Habilidad para comprender rápidamente los cambios del entorno, detectar oportunidades, reconocer amenazas competitivas y evaluar fortalezas y debilidades organizacionales para identificar la mejor respuesta estratégica. Incluye detectar oportunidades de negocio y generar alianzas estratégicas.'
            ],
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Capacidad de escuchar, hacer preguntas y expresar conceptos e ideas en forma efectiva. Comprende entender la dinámica de grupos y diseñar reuniones efectivas, así como comunicar por escrito con claridad.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Capacidad de idear soluciones a conflictos atendiendo necesidades, problemas y objetivos del negocio, evitando manipulación y parcialidad en diferencias entre dos o más partes.'
            ],
            [
                'nombre' => 'Análisis Numérico',
                'descripcion' => 'Capacidad para analizar, organizar y presentar datos numéricos de manera exacta. Propia de cargos relacionados con áreas contables y financieras.'
            ],
            [
                'nombre' => 'Servicio al cliente',
                'descripcion' => 'Deseo de ayudar o servir a los clientes, comprender y satisfacer necesidades incluso no expresadas. Implica resolver problemas del cliente y mantener una actitud permanente de considerar sus necesidades para planificar la actividad.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Preocupación constante por revisar y controlar el trabajo y la información, asegurando que las funciones asignadas estén claramente definidas.'
            ],
            [
                'nombre' => 'Dinamismo – energía',
                'descripcion' => 'Habilidad para trabajar arduamente en situaciones cambiantes o alternativas, con interlocutores diversos y jornadas prolongadas sin disminuir el nivel de actividad.'
            ]
        ],

        'Coordinadora Administrativa' => [
            [
                'nombre' => 'Liderazgo',
                'descripcion' => 'Es la capacidad para dirigir a un grupo o equipo de trabajo. Implica el deseo de guiar a los demás, crear un clima de energía y compromiso, y comunicar la visión de la empresa desde una posición formal o informal de autoridad.'
            ],
            [
                'nombre' => 'Trabajo en Equipo',
                'descripcion' => 'Habilidad para actuar con eficacia bajo presión, desacuerdos, oposición o diversidad. Capacidad para mantener un alto desempeño incluso en situaciones de mucha exigencia.'
            ],
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Capacidad de escuchar, hacer preguntas, expresar ideas con claridad, comprender a los demás, entender la dinámica de grupos y diseñar reuniones efectivas. Incluye comunicar por escrito con concisión y claridad.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Capacidad para idear soluciones a conflictos considerando necesidades, problemas y objetivos del negocio. Incluye resolver diferencias entre dos o más partes evitando manipulación o parcialidad.'
            ],
            [
                'nombre' => 'Servicio al cliente',
                'descripcion' => 'Deseo de servir y comprender las necesidades del cliente, incluso las no expresadas. Implica resolver problemas y mantener una actitud permanente de considerar las necesidades del cliente al planificar actividades.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Preocupación constante por comprobar y controlar el trabajo y la información, asegurando que las funciones asignadas estén claramente definidas.'
            ],
            [
                'nombre' => 'Dinamismo – energía',
                'descripcion' => 'Habilidad para trabajar con alto rendimiento en situaciones cambiantes, con interlocutores diversos y en jornadas prolongadas sin disminuir el nivel de actividad.'
            ]
        ],

        'Ejecutivo Comercial' => [
            [
                'nombre' => 'Pensamiento estratégico',
                'descripcion' => 'Capacidad para comprender cambios del entorno, evaluar oportunidades y amenazas, y reconocer fortalezas y debilidades de la organización para identificar la mejor respuesta estratégica. Incluye detectar oportunidades y generar alianzas.'
            ],
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Capacidad de escuchar, preguntar y comunicar ideas con claridad. Comprende la dinámica de grupos y la comunicación escrita precisa.'
            ],
            [
                'nombre' => 'Capacidad de Planificación y Organización',
                'descripcion' => 'Preocupación constante por controlar el trabajo y la información, asegurando que responsabilidades y funciones estén claramente asignadas.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Capacidad para generar soluciones ante conflictos considerando necesidades del negocio y evitando parcialidad o manipulación.'
            ],
            [
                'nombre' => 'Orientación al cliente interno y externo',
                'descripcion' => 'Sensibilidad para identificar necesidades presentes o futuras de clientes internos o externos. Compromiso con la calidad y la mejora continua, escuchando al cliente y generando soluciones.'
            ],
            [
                'nombre' => 'Orientación a Resultados',
                'descripcion' => 'Capacidad para actuar con urgencia al tomar decisiones clave, superar competidores, atender al cliente y gestionar procesos que permitan alcanzar los resultados esperados.'
            ],
            [
                'nombre' => 'Aprendizaje Continuo',
                'descripcion' => 'Capacidad para buscar y compartir información útil para resolver situaciones de negocio. Incluye capitalizar experiencia propia y ajena, y difundir conocimientos adquiridos.'
            ],
            [
                'nombre' => 'Negociación',
                'descripcion' => 'Habilidad para generar un ambiente colaborativo y lograr acuerdos duraderos utilizando técnicas ganar-ganar. Se enfoca en el problema y no en la persona.'
            ],
            [
                'nombre' => 'Manejo de Relaciones de Negocios',
                'descripcion' => 'Capacidad para crear y mantener una red de contactos útiles para cumplir metas relacionadas con el trabajo.'
            ]
        ],

        'Gerente comercial' => [
            [
                'nombre' => 'Liderazgo',
                'descripcion' => 'Capacidad para dirigir equipos de trabajo, motivar, comunicar la visión organizacional y generar compromiso desde una posición formal o informal.'
            ],
            [
                'nombre' => 'Trabajo en Equipo',
                'descripcion' => 'Habilidad para mantener eficacia bajo presión, oposición o diversidad, respondiendo con alto desempeño en situaciones de alta exigencia.'
            ],
            [
                'nombre' => 'Desarrollo de las Personas',
                'descripcion' => 'Esfuerzo constante por mejorar la formación y desarrollo propio y de los demás mediante el análisis adecuado de necesidades individuales y organizacionales.'
            ],
            [
                'nombre' => 'Aprendizaje Continuo',
                'descripcion' => 'Habilidad para buscar, compartir y aplicar información útil para resolver situaciones de negocio, aprovechando experiencia propia y de otros.'
            ],
            [
                'nombre' => 'Orientación a Resultados',
                'descripcion' => 'Capacidad para actuar con urgencia en decisiones importantes, superar competidores y mejorar la organización sin que los procesos interfieran con los resultados.'
            ],
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Capacidad para escuchar, preguntar y comunicar ideas clara y eficazmente, así como entender la dinámica de grupos y escribir con precisión.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Capacidad para idear soluciones a conflictos considerando necesidades y objetivos organizacionales, evitando sesgos y mediando entre partes.'
            ],
            [
                'nombre' => 'Servicio al cliente',
                'descripcion' => 'Actitud permanente de comprender y satisfacer las necesidades del cliente, incluso las no expresadas, incorporándolas en la planificación de actividades.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Preocupación por revisar, controlar y organizar el trabajo y la información asegurando claridad en responsabilidades asignadas.'
            ],
            [
                'nombre' => 'Dinamismo – energía',
                'descripcion' => 'Habilidad para mantenerse activo y productivo en situaciones cambiantes, con interlocutores diversos y en jornadas largas.'
            ],
            [
                'nombre' => 'Manejo de Relaciones de Negocios',
                'descripcion' => 'Capacidad para crear y sostener redes de contactos útiles para cumplir los objetivos del trabajo.'
            ]
        ],

        'Jefe comercial outsourcing' => [
            [
                'nombre' => 'Liderazgo',
                'descripcion' => 'Capacidad para dirigir equipos de trabajo, motivar, comunicar la visión organizacional y generar compromiso desde una posición formal o informal.'
            ],
            [
                'nombre' => 'Trabajo en Equipo',
                'descripcion' => 'Habilidad para mantener eficacia bajo presión, desacuerdos o diversidad, trabajando con alto desempeño en situaciones exigentes.'
            ],
            [
                'nombre' => 'Desarrollo de las Personas',
                'descripcion' => 'Esfuerzo constante por mejorar la formación y desarrollo de sí mismo y de los demás mediante análisis adecuado de necesidades.'
            ],
            [
                'nombre' => 'Aprendizaje Continuo',
                'descripcion' => 'Capacidad para buscar y compartir información útil para resolver situaciones de negocio, capitalizando experiencia propia y ajena.'
            ],
            [
                'nombre' => 'Orientación a Resultados',
                'descripcion' => 'Capacidad para actuar con sentido de urgencia en decisiones importantes, superar competidores y cumplir objetivos sin que los procesos interfieran.'
            ],
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Habilidad para escuchar, preguntar, comprender ideas y comunicar de forma clara en contextos formales e informales.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Capacidad para idear soluciones y resolver diferencias entre partes con imparcialidad y teniendo en cuenta los objetivos organizacionales.'
            ],
            [
                'nombre' => 'Servicio al cliente',
                'descripcion' => 'Actitud orientada a ayudar al cliente, comprender sus necesidades e incorporarlas a la planificación para asegurar su satisfacción.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Capacidad para controlar información y trabajo, asegurando claridad y correcta asignación de responsabilidades.'
            ],
            [
                'nombre' => 'Dinamismo – energía',
                'descripcion' => 'Habilidad para trabajar en entornos cambiantes, con diversos interlocutores y jornadas prolongadas manteniendo altos niveles de actividad.'
            ],
            [
                'nombre' => 'Manejo de Relaciones de Negocios',
                'descripcion' => 'Capacidad de crear y mantener redes de contactos útiles para alcanzar metas relacionadas al trabajo.'
            ]
        ],

        'Jefe Comercial Sucursal' => [
            [
                'nombre' => 'Liderazgo',
                'descripcion' => 'Es la capacidad para dirigir a un grupo o equipo de trabajo. Implica el deseo de guiar a los demás. Los líderes crean un clima de energía y compromiso y comunican la visión de la empresa, ya sea desde una posición formal o informal de autoridad. El "equipo" debe considerarse en sentido amplio, como cualquier grupo en el que la persona asume el papel de líder.'
            ],
            [
                'nombre' => 'Trabajo en Equipo',
                'descripcion' => 'Se trata de la habilidad para seguir actuando con efi­cacia en situaciones de presión de tiempo y de desacuerdo, oposición y diversidad. Es la capacidad para responder y trabajar con alto desempeño en situaciones de mucha exigencia.'
            ],
            [
                'nombre' => 'Desarrollo de las Personas',
                'descripcion' => 'Implica un esfuerzo constante por mejorar la formación y el desarrollo, tanto los personales como los de los demás, a partir de un apropiado análisis previo de sus necesidades y de la organización. No se trata sólo de enviar a las personas a cursos, sino de un esfuerzo por desarrollar a los demás.'
            ],
            [
                'nombre' => 'Aprendizaje Continuo',
                'descripcion' => '(Por generación propia y aportado por la comunidad profesio­nal); Es la habilidad para buscar y compartir información útil para la resolución de situa­ciones de negocios, utilizando todo el potencial de la empresa (o corporación, según co­rresponda). Incluye la capacidad de capitalizar la experiencia de otros y la propia, propagando el know how adquirido en foros locales o internacionales.'
            ],
            [
                'nombre' => 'Orientación a Resultados',
                'descripcion' => 'Es la capacidad para actuar con velocidad y sentido de urgencia cuando son necesarias decisiones importantes para cumplir con los competidores o superarlos, atender las necesidades del cliente o mejorar a la organización. Es capaz de administrar los procesos establecidos para que no interfieran con la consecución de los resultados esperados.'
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
            ],
            [
                'nombre' => 'Manejo de Relaciones de Negocios',
                'descripcion' => 'Es la habilidad para crear y mantener una red de contactos con personas que son o serán útiles para alcanzar las metas relacionadas con el trabajo.'
            ]
        ],

        'Pasante Comercial' => [
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad.'
            ],
            [
                'nombre' => 'Orientación al cliente interno y externo',
                'descripcion' => 'Demostrar sensibilidad por las necesidades o exigencias que un conjunto de clientes potenciales externos o internos pueden requerir en el presente o en el futuro. No se trata tanto de una conducta concreta frente a un cliente real como de una actitud permanente de contar con las necesidades del cliente para incorporar este conocimiento a la forma específica de planear a la actividad. Se la diferencia de "atención al cliente", que tiene más relación con atender las necesidades de un cliente real y concreto en la interacción. Conceder la más alta calidad a la satisfacción del cliente. Escuchar al cliente. Generar soluciones para satisfacer las necesidades de los clientes. Estar comprometido con la calidad, esforzándose por una mejora continua.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica también una insistencia en que las responsabilidades y funciones asignadas estén claramente asignadas.'
            ],
            [
                'nombre' => 'Aprendizaje Continuo',
                'descripcion' => 'Es la habilidad para buscar y compartir información útil para la resolución de situaciones de negocios, utilizando todo el potencial de la empresa (o corporación, según corresponda). Incluye la capacidad de capitalizar la experiencia de otros y la propia, propagando el know how adquirido en foros locales o internacionales.'
            ],
            [
                'nombre' => 'Dinamismo - energía',
                'descripcion' => 'Se trata de la habilidad para trabajar duro en situaciones cambiantes o alternativas, con interlocutores muy diversos, que cambian en cortos espacios de tiempo, en jornadas de trabajo prolongadas sin que por esto se vea afectado su nivel de actividad.'
            ]
        ],

        'Asistente Administrativa Comercial' => [
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Es la capacidad de idear la solución a un conflicto, atendiendo sus necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la capacidad de idear soluciones para resolver diferencias de ideas u opiniones 2 o más partes, evitando la manipulación y la parcialidad de los intereses personales.'
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

        'Auxiliar Comercial' => [
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Es la capacidad de idear la solución a un conflicto, atendiendo sus necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la capacidad de idear soluciones para resolver diferencias de ideas u opiniones 2 o más partes, evitando la manipulación y la parcialidad de los intereses personales.'
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

        'Asistente Comercial Administrativo GYE' => [
            [
                "competencia" => "Comunicación efectiva",
                "descripcion" => "Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad"
            ],
            [
                "competencia" => "Manejo de conflictos",
                "descripcion" => "Es la capacidad de idear la solución a un conflicto, atendiendo sus necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la capacidad de idear soluciones para resolver diferencias de ideas u opiniones 2 o más partes, evitando la manipulación y la parcialidad de los intereses personales."
            ],
            [
                "competencia" => "Servicio al cliente",
                "descripcion" => "Implica un deseo de ayudar o servir a los clientes, de comprender y satisfacer sus necesidades, aun aquéllas no expresadas. Implica esforzarse por conocer y resolver los problemas del cliente, tanto del cliente final a quien van dirigidos los esfuerzos de la empresa como los clientes de los propios clientes y todos aquellos que cooperen en la relación empresa-cliente, como el personal ajeno a la organización. No se trata tanto de una conducta concreta frente a un cliente real como de una actitud permanente de contar con las necesidades del cliente para incorporar este conocimiento a la forma específica de planificar la actividad."
            ],
            [
                "competencia" => "Orden y Organización",
                "descripcion" => "Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica también una insistencia en que las responsabilidades y funciones asignadas estén claramente asignadas."
            ],
            [
                "competencia" => "Dinamismo – energía",
                "descripcion" => "Se trata de la habilidad para trabajar duro en situaciones cambiantes o alternativas, con interlocutores muy diversos, que cambian en cortos espacios de tiempo, en jornadas de trabajo prolongadas sin que por esto se vea afectado su nivel de actividad."
            ]
        ],

        'Asistente Administrativo Sucursal' => [
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Es la capacidad de idear la solución a un conflicto, atendiendo sus necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la capacidad de idear soluciones para resolver diferencias de ideas u opiniones 2 o más partes, evitando la manipulación y la parcialidad de los intereses personales.'
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
            ],
            [
                'nombre' => 'Orientacion al cliente interno y externo',
                'descripcion' => 'Demostrar sensibilidad por las necesidades o exigencias que un conjunto de clientes potenciales externos o internos pueden requerir en el presente o en el futuro. No se trata tanto de una conducta concreta frente a un cliente real como de una actitud permanente de contar con las necesidades del cliente para incorporar este conocimiento a la forma específica de planear a la actividad. Se la diferencia de "atención al cliente", que tiene más relación con atender las necesidades de un cliente real y concreto en la interacción. Conceder la más alta calidad a la satisfacción del cliente. Escuchar al cliente. Generar Soluciones para satisfacer las necesidades de los clientes. Estar comprometido con la calidad, esforzándose por una mejora continua.'
            ]
        ],

        'Analista Administrativo Proyectos' => [
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Es la capacidad de idear la solución a un conflicto, atendiendo sus necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la capacidad de idear soluciones para resolver diferencias de ideas u opiniones 2 o más partes, evitando la manipulación y la parcialidad de los intereses personales.'
            ],
            [
                'nombre' => 'Servicio al cliente',
                'descripcion' => 'Implica un deseo de ayudar o servir a los clientes, de comprender y satisfacer sus necesidades, aun aquéllas no expresadas. Implica esforzarse por conocer y resolver los problemas del cliente, tanto del cliente final a quien van dirigidos los esfuerzos de la empresa como los clientes de los propios clientes y todos aquellos que cooperen en la relación empresa-cliente, como el personal ajeno a la organización. No se trata tanto de una conducta concreta frente a un cliente real como de una actitud permanente de contar con las necesidades del cliente para incorporar este conocimiento a la forma específica de planificar la actividad.'
            ],
            [
                'nombre' => 'Planificación y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica también una insistencia en que las responsabilidades y funciones estén claramente asignadas.'
            ],
            [
                'nombre' => 'Dinamismo – energía',
                'descripcion' => 'Se trata de la habilidad para trabajar duro en situaciones cambiantes o alternativas, con interlocutores muy diversos, que cambian en cortos espacios de tiempo, en jornadas de trabajo prolongadas sin que por esto se vea afectado su nivel de actividad.'
            ],
            [
                'nombre' => 'Orientación al cliente interno y externo',
                'descripcion' => 'Demostrar sensibilidad por las necesidades o exigencias que un conjunto de clientes potenciales externos o internos pueden requerir en el presente o en el futuro. No se trata tanto de una conducta concreta frente a un cliente real como de una actitud permanente de contar con las necesidades del cliente para incorporar este conocimiento a la forma específica de planear a la actividad. Se la diferencia de "atención al cliente", que tiene más relación con atender las necesidades de un cliente real y concreto en la interacción. Conceder la más alta calidad a la satisfacción del cliente. Escuchar al cliente. Generar Soluciones para satisfacer las necesidades de los clientes. Estar comprometido con la calidad, esforzándose por una mejora continua.'
            ],
            [
                'nombre' => 'Pensamiento Estratégico',
                'descripcion' => 'Es la habilidad para comprender rápidamente los cambios del entorno, las oportunidades del mercado, las amenazas competitivas y las fortalezas y debilidades de su propia organización a la hora de identificar la mejor respuesta estratégica. Capacidad para detectar nuevas oportunidades de negocios, comprar negocios en marcha, o realizar alianzas estratégicas con clientes, proveedores y competidores. Incluye la capacidad para saber cuándo hay que abandonar un negocio o reemplazarlo por otro.'
            ]
        ],

        'Analista de Sistemas Proyectos' => [
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
                'nombre' => 'Orientación al cliente interno y externo',
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
        ],

        'Asistente Administrativo Outsourcing' => [
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
            ],
            [
                'nombre' => 'Trabajo en Equipo',
                'descripcion' => 'Es la capacidad de participar activamente en la prosecución de una meta común, subordinando los intereses personales a los objetivos del equipo.'
            ]
        ],

        'Coordinador de Outsourcing' => [
            [
                'nombre' => 'Pensamiento estratégico',
                'descripcion' => 'Es la habilidad para comprender rápidamente los cambios del entorno, las oportunidades del mercado, las amenazas competitivas y las fortalezas y debilidades de su propia organización a la hora de identificar la mejor respuesta estratégica. Capacidad para detectar nuevas oportunidades de negocio y realizar alianzas estratégicas.'
            ],
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Es la capacidad de idear la solución a un conflicto, atendiendo sus necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la capacidad de idear soluciones para resolver diferencias de ideas u opiniones entre dos o más partes, evitando la manipulación y la parcialidad de los intereses personales.'
            ],
            [
                'nombre' => 'Orientación al cliente interno y externo',
                'descripcion' => 'Demostrar sensibilidad por las necesidades o exigencias que un conjunto de clientes potenciales externos o internos pueden requerir en el presente o futuro. Implica una actitud permanente de considerar las necesidades del cliente y utilizar ese conocimiento para planificar adecuadamente la actividad. Diferente de la atención al cliente, que se centra en la interacción puntual. Involucra escuchar, generar soluciones y comprometerse con la calidad y la mejora continua.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica también una insistencia en que las responsabilidades y funciones asignadas estén claramente definidas y correctamente ejecutadas.'
            ],
            [
                'nombre' => 'Aprendizaje Continuo',
                'descripcion' => 'Es la habilidad para buscar y compartir información útil para la resolución de situaciones de negocios, utilizando el potencial de la empresa. Incluye la capacidad de capitalizar la experiencia propia y ajena, promoviendo el conocimiento adquirido en espacios locales o internacionales.'
            ],
            [
                'nombre' => 'Dinamismo - energía',
                'descripcion' => 'Se trata de la habilidad para trabajar arduamente en situaciones cambiantes, con interlocutores diversos y condiciones variables en cortos períodos de tiempo, manteniendo un nivel de actividad constante aun en jornadas extensas.'
            ]
        ],

        'Ejecutivo Proyectos' => [
            [
                'nombre' => 'Pensamiento estratégico',
                'descripcion' => 'Es la habilidad para comprender rápidamente los cambios del entorno, las oportunidades del mercado, las amenazas competitivas y las fortalezas y debilidades de su propia organización a la hora de identificar la mejor respuesta estratégica. Capacidad para detectar nuevas oportunidades de negocio y realizar alianzas estratégicas.'
            ],
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad.'
            ],
            [
                'nombre' => 'Capacidad de Planificación y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica también una insistencia en que las responsabilidades y funciones estén claramente asignadas.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Es la capacidad de idear la solución a un conflicto, atendiendo sus necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la capacidad de idear soluciones para resolver diferencias de ideas u opiniones entre dos o más partes, evitando la manipulación y la parcialidad de los intereses personales.'
            ],
            [
                'nombre' => 'Orientación al cliente interno y externo',
                'descripcion' => 'Demostrar sensibilidad por las necesidades o exigencias que un conjunto de clientes potenciales externos o internos pueden requerir en el presente o en el futuro. Implica una actitud permanente de considerar las necesidades del cliente para planear la actividad. Diferente de la atención al cliente, que se enfoca en la interacción directa. Conceder alta calidad a la satisfacción del cliente, escuchar, generar soluciones y mantener compromiso con la mejora continua.'
            ],
            [
                'nombre' => 'Orientación a Resultados',
                'descripcion' => 'Es la capacidad para actuar con velocidad y sentido de urgencia cuando se requieren decisiones importantes para cumplir o superar a los competidores, atender necesidades del cliente o mejorar la organización. Capacidad para administrar procesos sin que interfieran con la consecución de los resultados esperados.'
            ],
            [
                'nombre' => 'Aprendizaje Continuo',
                'descripcion' => 'Es la habilidad para buscar y compartir información útil para la resolución de situaciones de negocios, utilizando todo el potencial de la empresa. Incluye la capacidad de capitalizar la experiencia propia y de otros, difundiendo el conocimiento adquirido en espacios locales o internacionales.'
            ],
            [
                'nombre' => 'Negociación',
                'descripcion' => 'Habilidad para crear un ambiente propicio para la colaboración y lograr compromisos duraderos que fortalezcan la relación. Capacidad para dirigir o controlar una discusión utilizando enfoques ganar-ganar, planificando alternativas para obtener los mejores acuerdos. Se centra en el problema y no en la persona.'
            ],
            [
                'nombre' => 'Manejo de Relaciones de Negocios',
                'descripcion' => 'Es la habilidad para crear y mantener una red de contactos con personas que son o serán útiles para alcanzar las metas relacionadas con el trabajo.'
            ]
        ],

        'Asistente de Mesa de Ayuda' => [
            [
                'nombre' => 'Pensamiento estratégico',
                'descripcion' => 'Es la habilidad para comprender rápidamente los cambios del entorno, las oportunidades del mercado, las amenazas competitivas y las fortalezas y debilidades de su propia organización a la hora de identificar la mejor respuesta estratégica. Capacidad para detectar nuevas oportunidades de negocio y realizar alianzas estratégicas.'
            ],
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Es la capacidad de idear la solución a un conflicto, atendiendo sus necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la capacidad de idear soluciones para resolver diferencias de ideas u opiniones entre dos o más partes, evitando la manipulación y la parcialidad de los intereses personales.'
            ],
            [
                'nombre' => 'Orientación al cliente interno y externo',
                'descripcion' => 'Demostrar sensibilidad por las necesidades o exigencias que un conjunto de clientes potenciales externos o internos pueden requerir en el presente o en el futuro. Implica una actitud permanente de considerar las necesidades del cliente para planear la actividad. Diferente de la atención al cliente, que se enfoca en atender un caso concreto. Conceder alta calidad a la satisfacción del cliente, escuchar, generar soluciones y mantener compromiso con la mejora continua.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica también una insistencia en que las responsabilidades y funciones asignadas estén claramente asignadas.'
            ],
            [
                'nombre' => 'Aprendizaje Continuo',
                'descripcion' => 'Es la habilidad para buscar y compartir información útil para la resolución de situaciones de negocios, utilizando todo el potencial de la empresa. Incluye la capacidad de capitalizar la experiencia propia y de otros, propagando el conocimiento adquirido en espacios locales o internacionales.'
            ],
            [
                'nombre' => 'Dinamismo - energía',
                'descripcion' => 'Se trata de la habilidad para trabajar duro en situaciones cambiantes o alternativas, con interlocutores diversos, que cambian en cortos espacios de tiempo, en jornadas de trabajo prolongadas sin que esto afecte su nivel de actividad.'
            ]
        ],

        'Operador Informático Adelca' => [
            [
                'nombre' => 'Pensamiento estratégico',
                'descripcion' => 'Es la habilidad para comprender rápidamente los cambios del entorno, las oportunidades del mercado, las amenazas competitivas y las fortalezas y debilidades de su propia organización a la hora de identificar la mejor respuesta estratégica. Capacidad para detectar nuevas oportunidades de negocio y realizar alianzas estratégicas.'
            ],
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Es la capacidad de idear la solución a un conflicto, atendiendo sus necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la capacidad de idear soluciones para resolver diferencias de ideas u opiniones entre dos o más partes, evitando la manipulación y la parcialidad de los intereses personales.'
            ],
            [
                'nombre' => 'Orientación al cliente interno y externo',
                'descripcion' => 'Demostrar sensibilidad por las necesidades o exigencias que un conjunto de clientes potenciales externos o internos pueden requerir en el presente o en el futuro. Implica una actitud permanente de considerar las necesidades del cliente para planear la actividad. Conceder alta calidad a la satisfacción del cliente, escuchar, generar soluciones y mantener un compromiso con la mejora continua.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica también una insistencia en que las responsabilidades y funciones asignadas estén claramente asignadas.'
            ],
            [
                'nombre' => 'Aprendizaje Continuo',
                'descripcion' => 'Es la habilidad para buscar y compartir información útil para la resolución de situaciones de negocios, utilizando todo el potencial de la empresa. Incluye la capacidad de capitalizar la experiencia propia y de otros, propagando el conocimiento adquirido en foros locales o internacionales.'
            ],
            [
                'nombre' => 'Dinamismo - energía',
                'descripcion' => 'Se trata de la habilidad para trabajar duro en situaciones cambiantes o alternativas, con interlocutores diversos, que cambian en cortos espacios de tiempo, en jornadas de trabajo prolongadas sin que esto afecte su nivel de actividad.'
            ]
        ],

        'Técnico' => [
            [
                'nombre' => 'Pensamiento estratégico',
                'descripcion' => 'Es la habilidad para comprender rápidamente los cambios del entorno, las oportunidades del mercado, las amenazas competitivas y las fortalezas y debilidades de su propia organización a la hora de identificar la mejor respuesta estratégica. Capacidad para detectar nuevas oportunidades de negocio y realizar alianzas estratégicas.'
            ],
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Es la capacidad de idear la solución a un conflicto, atendiendo sus necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la capacidad de idear soluciones para resolver diferencias de ideas u opiniones entre dos o más partes, evitando la manipulación y la parcialidad de los intereses personales.'
            ],
            [
                'nombre' => 'Orientación al cliente interno y externo',
                'descripcion' => 'Demostrar sensibilidad por las necesidades o exigencias que un conjunto de clientes potenciales externos o internos pueden requerir en el presente o en el futuro. Implica una actitud permanente de considerar las necesidades del cliente para incorporar este conocimiento a la forma de planear la actividad. Conceder alta calidad a la satisfacción del cliente, escuchar, generar soluciones y mantener un compromiso con la mejora continua.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica también una insistencia en que las responsabilidades y funciones asignadas estén claramente definidas.'
            ],
            [
                'nombre' => 'Aprendizaje Continuo',
                'descripcion' => 'Es la habilidad para buscar y compartir información útil para la resolución de situaciones de negocios, utilizando todo el potencial de la empresa. Incluye la capacidad de capitalizar la experiencia de otros y la propia, propagando el conocimiento adquirido en foros locales o internacionales.'
            ],
            [
                'nombre' => 'Dinamismo - energía',
                'descripcion' => 'Se trata de la habilidad para trabajar duro en situaciones cambiantes o alternativas, con interlocutores diversos que cambian en cortos espacios de tiempo, en jornadas prolongadas sin que esto afecte su nivel de actividad.'
            ]
        ],

        'Técnico Costa' => [
            [
                'nombre' => 'Pensamiento estratégico',
                'descripcion' => 'Es la habilidad para comprender rápidamente los cambios del entorno, las oportunidades del mercado, las amenazas competitivas y las fortalezas y debilidades de su propia organización a la hora de identificar la mejor respuesta estratégica. Capacidad para detectar nuevas oportunidades de negocio y realizar alianzas estratégicas.'
            ],
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Es la capacidad de idear la solución a un conflicto, atendiendo sus necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la capacidad de idear soluciones para resolver diferencias de ideas u opiniones entre dos o más partes, evitando la manipulación y la parcialidad de los intereses personales.'
            ],
            [
                'nombre' => 'Orientación al cliente interno y externo',
                'descripcion' => 'Demostrar sensibilidad por las necesidades o exigencias que un conjunto de clientes potenciales externos o internos pueden requerir en el presente o en el futuro. Implica una actitud permanente de considerar las necesidades del cliente para incorporar este conocimiento a la forma de planear la actividad. Conceder alta calidad a la satisfacción del cliente, escuchar, generar soluciones y mantener un compromiso con la mejora continua.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica también una insistencia en que las responsabilidades y funciones asignadas estén claramente definidas.'
            ],
            [
                'nombre' => 'Aprendizaje Continuo',
                'descripcion' => 'Es la habilidad para buscar y compartir información útil para la resolución de situaciones de negocios, utilizando todo el potencial de la empresa. Incluye la capacidad de capitalizar la experiencia de otros y la propia, propagando el conocimiento adquirido en foros locales o internacionales.'
            ],
            [
                'nombre' => 'Dinamismo - energía',
                'descripcion' => 'Se trata de la habilidad para trabajar duro en situaciones cambiantes o alternativas, con interlocutores diversos que cambian en cortos espacios de tiempo, en jornadas prolongadas sin que esto afecte su nivel de actividad.'
            ]
        ],
        'Técnico Cuenca' => [
            [
                'nombre' => 'Pensamiento estratégico',
                'descripcion' => 'Es la habilidad para comprender rápidamente los cambios del entorno, las oportunidades del mercado, las amenazas competitivas y las fortalezas y debilidades de su propia organización a la hora de identificar la mejor respuesta estratégica. Capacidad para detectar nuevas oportunidades de negocio y realizar alianzas estratégicas.'
            ],
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Es la capacidad de idear la solución a un conflicto, atendiendo sus necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la capacidad de idear soluciones para resolver diferencias de ideas u opiniones 2 o más partes, evitando la manipulación y la parcialidad de los intereses personales.'
            ],
            [
                'nombre' => 'Orientación al cliente interno y externo',
                'descripcion' => 'Demostrar sensibilidad por las necesidades o exigencias que un conjunto de clientes potenciales externos o internos pueden requerir en el presente o en el futuro. Actitud permanente de considerar las necesidades del cliente para incorporarlas en la planificación y ejecución de actividades. Conceder la más alta calidad a la satisfacción del cliente, escuchar, generar soluciones y mantener compromiso con la mejora continua.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica también una insistencia en que las responsabilidades y funciones asignadas estén claramente definidas y estructuradas.'
            ],
            [
                'nombre' => 'Aprendizaje Continuo',
                'descripcion' => 'Es la habilidad para buscar y compartir información útil para la resolución de situaciones de negocios, utilizando todo el potencial de la organización. Incluye la capacidad de capitalizar experiencia propia y ajena, difundiendo el conocimiento adquirido.'
            ],
            [
                'nombre' => 'Dinamismo - energía',
                'descripcion' => 'Habilidad para trabajar con intensidad en situaciones cambiantes, con interlocutores diversos y bajo condiciones demandantes o extensas, manteniendo su nivel de productividad y energía.'
            ]
        ],

        'Técnico Quito' => [
            [
                'nombre' => 'Pensamiento estratégico',
                'descripcion' => 'Es la habilidad para comprender rápidamente los cambios del entorno, las oportunidades del mercado, las amenazas competitivas y las fortalezas y debilidades de su propia organización a la hora de identificar la mejor respuesta estratégica. Capacidad para detectar nuevas oportunidades de negocio y realizar alianzas estratégicas.'
            ],
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Es la capacidad de idear la solución a un conflicto, atendiendo sus necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la capacidad de idear soluciones para resolver diferencias de ideas u opiniones entre 2 o más partes, evitando la manipulación y la parcialidad de los intereses personales.'
            ],
            [
                'nombre' => 'Orientación al cliente interno y externo',
                'descripcion' => 'Demostrar sensibilidad por las necesidades presentes o futuras de clientes internos o externos. Actitud permanente de considerar dichas necesidades en la planificación y ejecución de actividades. Se diferencia de la atención al cliente, pues implica una perspectiva estratégica. Incluye escuchar activamente, generar soluciones, garantizar calidad y mantener el compromiso con la mejora continua.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica una insistencia en que las responsabilidades y funciones asignadas estén claramente definidas y organizadas.'
            ],
            [
                'nombre' => 'Aprendizaje Continuo',
                'descripcion' => 'Es la habilidad para buscar y compartir información útil para resolver situaciones de negocio, usando el potencial de la organización. Incluye capitalizar la experiencia propia y de otros, difundiendo el conocimiento adquirido en diversos contextos.'
            ],
            [
                'nombre' => 'Dinamismo - energía',
                'descripcion' => 'Habilidad para trabajar intensamente en situaciones cambiantes o alternativas, con interlocutores diversos, manteniendo el nivel de actividad incluso en jornadas extensas o bajo presión.'
            ]
        ],

        'Jefe Técnico' => [
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva, exponer aspectos positivos. La habilidad de saber cuándo y a quién preguntar para llevar adelante un propósito. Es la capacidad de escuchar al otro y comprenderlo. Comprender la dinámica de grupos y el diseño efectivo de reuniones. Incluye la capacidad de comunicar por escrito con concisión y claridad.'
            ],
            [
                'nombre' => 'Manejo de conflictos',
                'descripcion' => 'Es la capacidad de idear la solución a un conflicto, atendiendo las necesidades, problemas y objetivos del negocio con la factibilidad interna de resolución. Incluye la habilidad para resolver diferencias de ideas u opiniones entre dos o más partes, evitando la manipulación y la parcialidad de los intereses personales.'
            ],
            [
                'nombre' => 'Servicio al cliente',
                'descripcion' => 'Implica un deseo permanente de ayudar o servir a los clientes, comprendiendo y satisfaciendo sus necesidades, incluso aquellas no expresadas. Supone conocer y resolver los problemas del cliente, ya sea el cliente final, los clientes internos o terceros involucrados en la relación empresa-cliente. Es una actitud constante de considerar las necesidades del cliente en la planificación de las actividades.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por comprobar y controlar el trabajo y la información. Implica una insistencia en que las responsabilidades, tareas y funciones asignadas estén claramente establecidas y organizadas.'
            ],
            [
                'nombre' => 'Pensamiento Estratégico',
                'descripcion' => 'Es la habilidad para comprender rápidamente los cambios del entorno, las oportunidades del mercado, las amenazas competitivas y las fortalezas y debilidades de la organización para identificar la mejor respuesta estratégica. Incluye detectar oportunidades de negocio, evaluar alianzas estratégicas, adquirir negocios en marcha o decidir abandonar actividades que ya no generan valor.'
            ],
            [
                'nombre' => 'Pensamiento Analítico',
                'descripcion' => 'Es la capacidad para comprender, descomponer y analizar situaciones complejas, evaluando datos y relaciones causales para tomar decisiones informadas. Implica identificar patrones, inconsistencias, riesgos y oportunidades, con el fin de formular conclusiones fundamentadas y soluciones efectivas.'
            ]
        ],

        'Analista de Sistemas Proyectos y TI' => [
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva y exponer aspectos positivos. Incluye saber cuándo y a quién consultar para avanzar en un propósito, comprender al interlocutor y dominar la dinámica de grupos y reuniones. También abarca la comunicación escrita clara y concisa.'
            ],
            [
                'nombre' => 'Orientación al cliente interno y externo',
                'descripcion' => 'Demostrar sensibilidad por las necesidades y exigencias de clientes internos o externos, tanto presentes como futuras. Implica una actitud permanente de considerar sus requerimientos al planificar actividades. Incluye escuchar activamente al cliente, generar soluciones y mantener un compromiso constante con la calidad y la mejora continua.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por verificar y controlar el trabajo y la información. Implica asegurar que las responsabilidades y funciones estén claramente definidas y estructuradas.'
            ],
            [
                'nombre' => 'Aprendizaje Continuo',
                'descripcion' => 'Es la habilidad para buscar, adquirir y compartir información útil para la resolución de problemas o situaciones de negocio, aprovechando al máximo los recursos de la organización. Incluye capitalizar la experiencia propia y ajena, difundiendo el conocimiento adquirido en espacios internos o externos.'
            ],
            [
                'nombre' => 'Dinamismo - energía',
                'descripcion' => 'Es la habilidad para trabajar con intensidad y efectividad en situaciones cambiantes, con interlocutores diversos y en escenarios que pueden variar en periodos cortos de tiempo. Permite mantener un alto nivel de actividad incluso en jornadas prolongadas sin afectar el rendimiento.'
            ]
        ],

        'Asistente de Sistemas' => [
            [
                'nombre' => 'Comunicación efectiva',
                'descripcion' => 'Es la capacidad de escuchar, hacer preguntas, expresar conceptos e ideas en forma efectiva y exponer aspectos positivos. Incluye saber cuándo y a quién consultar para avanzar en un propósito, comprender al interlocutor y dominar la dinámica de grupos y reuniones. También abarca la comunicación escrita clara y concisa.'
            ],
            [
                'nombre' => 'Orientación al cliente interno y externo',
                'descripcion' => 'Demostrar sensibilidad por las necesidades y exigencias de clientes internos o externos, tanto presentes como futuras. Implica una actitud permanente de considerar sus requerimientos al planificar actividades. Incluye escuchar activamente al cliente, generar soluciones y mantener un compromiso constante con la calidad y la mejora continua.'
            ],
            [
                'nombre' => 'Orden y Organización',
                'descripcion' => 'Es la preocupación continua por verificar y controlar el trabajo y la información. Implica asegurar que las responsabilidades y funciones estén claramente definidas y estructuradas.'
            ],
            [
                'nombre' => 'Aprendizaje Continuo',
                'descripcion' => 'Es la habilidad para buscar, adquirir y compartir información útil para la resolución de problemas o situaciones de negocio, aprovechando al máximo los recursos de la organización. Incluye capitalizar la experiencia propia y ajena, difundiendo el conocimiento adquirido en espacios internos o externos.'
            ],
            [
                'nombre' => 'Dinamismo - energía',
                'descripcion' => 'Es la habilidad para trabajar con intensidad y efectividad en situaciones cambiantes, con interlocutores diversos y en escenarios que pueden variar en periodos cortos de tiempo. Permite mantener un alto nivel de actividad incluso en jornadas prolongadas sin afectar el rendimiento.'
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