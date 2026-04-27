<body>
<style>
:root {
    --primary-color: #2563eb;
    --success-color: #22c55e;
    --error-color: #ef4444;
    --background-color: #f8fafc;
}

body {
    font-family: system-ui, -apple-system, sans-serif;
    margin: 0;
    padding: 0;
    background: var(--background-color);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

#vista_validacion_container {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    width: 90%;
    max-width: 800px;
    padding: 2rem;
    margin: 1rem;
}

#vista_validacion_header {
    text-align: center;
    margin-bottom: 2rem;
}

#vista_validacion_header h1 {
    color: #1e293b;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 1rem;
}

#vista_validacion_header p {
    color: #64748b;
    font-size: 15px;
}

.vista_validacion_checklist {
    list-style: none;
    padding: 0;
    margin: 2rem 0;
}

.vista_validacion_checklist li {
    display: flex;
    align-items: center;
    margin: 1rem 0;
    padding: 1rem;
    border-radius: 0.5rem;
    background: #f8fafc;
    transition: transform 0.2s;
}

.vista_validacion_checklist li:hover {
    transform: translateX(10px);
}

.vista_validacion_icon {
    font-size: 15px;
    margin-right: 1rem;
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.vista_validacion_success .vista_validacion_icon {
    background: rgba(34, 197, 94, 0.1);
    color: var(--success-color);
}

.vista_validacion_error .vista_validacion_icon {
    background: rgba(239, 68, 68, 0.1);
    color: var(--error-color);
}

.vista_validacion_text {
    flex: 1;
    font-size: 14px;
    color: #334155;
}

#vista_validacion_steps {
    margin: 2rem 0;
    padding: 1.5rem;
    background: #f1f5f9;
    border-radius: 0.5rem;
}

#vista_validacion_steps h2 {
    color: #334155;
    margin-top: 0;
    font-size: 14px;
}

#vista_validacion_steps ul {
    margin: 1rem 0;
    padding-left: 1.5rem;
    color: #64748b;
}

#vista_validacion_actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 2rem;
}

.vista_validacion_button {
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    background: var(--primary-color);
    color: white;
    text-decoration: none;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}

.vista_validacion_button:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
}

@media (max-width: 640px) {
    #vista_validacion_container {
        padding: 1rem;
    }

    #vista_validacion_header h1 {
        font-size: 20px;
    }

    .vista_validacion_checklist li {
        padding: 0.75rem;
    }
}
</style>

<div id="vista_validacion_container">
        <div id="vista_validacion_header">
            <h1>Validación de Acceso al POS</h1>
            <p>Para utilizar el punto de venta, asegúrate de cumplir con los siguientes requisitos:</p>
        </div>

        <ul class="vista_validacion_checklist">
            <li class="{% if validacion['tiene_sucursales_creadas'] %}vista_validacion_success{% else %}vista_validacion_error{% endif %}">
                <div class="vista_validacion_icon">
                    {% if validacion['tiene_sucursales_creadas'] %}✓{% else %}✕{% endif %}
                </div>
                <span class="vista_validacion_text">
                    {% if validacion['tiene_sucursales_creadas'] %}
                        Tienes sucursales activas creadas.
                    {% else %}
                        No tienes sucursales activas creadas.
                    {% endif %}
                </span>
            </li>

            <li class="{% if validacion['tiene_productos_creados'] %}vista_validacion_success{% else %}vista_validacion_error{% endif %}">
                <div class="vista_validacion_icon">
                    {% if validacion['tiene_productos_creados'] %}✓{% else %}✕{% endif %}
                </div>
                <span class="vista_validacion_text">
                    {% if validacion['tiene_productos_creados'] %}
                        La sucursal tiene productos activos creados.
                    {% else %}
                        La sucursal no tiene productos activos creados.
                    {% endif %}
                </span>
            </li>

            <li class="{% if validacion['el_usuario_tiene_permisos_para_usar_pos'] %}vista_validacion_success{% else %}vista_validacion_error{% endif %}">
                <div class="vista_validacion_icon">
                    {% if validacion['el_usuario_tiene_permisos_para_usar_pos'] %}✓{% else %}✕{% endif %}
                </div>
                <span class="vista_validacion_text">
                    {% if validacion['el_usuario_tiene_permisos_para_usar_pos'] %}
                        Si tienes permisos para ingresar al módulo POS.
                    {% else %}
                        No tienes permisos para ingresar al módulo POS.
                    {% endif %}
                </span>
            </li>

            {% if not esAdministrador %}
            <li class="{% if validacion['el_usuario_tiene_sucursal_asignada_activa'] %}vista_validacion_success{% else %}vista_validacion_error{% endif %}">
                <div class="vista_validacion_icon">
                    {% if validacion['el_usuario_tiene_sucursal_asignada_activa'] %}✓{% else %}✕{% endif %}
                </div>
                <span class="vista_validacion_text">
                    {% if validacion['el_usuario_tiene_sucursal_asignada_activa'] %}
                        Tienes una sucursal asignada y activa.
                    {% else %}
                        No tienes una sucursal asignada o está inactiva.
                    {% endif %}
                </span>
            </li>
            {% endif %}
        </ul>

        <div id="vista_validacion_steps">
            <h2>Pasos a seguir:</h2>
            <ul>
                {% if not validacion['tiene_sucursales_creadas'] %}
                    <li>Como administrador, debes crear al menos una sucursal activa.</li>
                {% endif %}

                {% if not validacion['tiene_productos_creados'] %}
                    <li>Debes agregar productos activos a tu sucursal.</li>
                {% endif %}

                {% if not validacion['el_usuario_tiene_permisos_para_usar_pos'] %}
                    <li>Debes Solicitar Acceso paar Utilizar el Módulo POS.</li>
                {% endif %}

                {% if not esAdministrador and not validacion['el_usuario_tiene_sucursal_asignada_activa'] %}
                    <li>Pide al administrador que te asigne una sucursal activa.</li>
                {% endif %}
            </ul>
        </div>

        <div id="vista_validacion_actions">
            {% if esAdministrador %}
                <a href="{{ url('/facturacionv8/branchoffice/lista_sucursales') }}" class="vista_validacion_button">Crear Sucursal</a>
                <a href="{{ url('/facturacionv8/producto/listaproductos') }}" class="vista_validacion_button">Agregar Producto</a>
            {% else %}
                <a href="{{ url('/facturacionv8/dashboard') }}" class="vista_validacion_button">Contactar al Administrador</a>
            {% endif %}
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Añadir animación de entrada
            $('.vista_validacion_checklist li').css('opacity', 0).each(function(i) {
                $(this).delay(i * 200).animate({
                    opacity: 1,
                    left: 0
                }, 500);
            });

            // Efecto hover en los botones
            $('.vista_validacion_button').hover(
                function() {
                    $(this).css('box-shadow', '0 4px 6px -1px rgba(0, 0, 0, 0.1)');
                },
                function() {
                    $(this).css('box-shadow', 'none');
                }
            );
        });
    </script>

</body>