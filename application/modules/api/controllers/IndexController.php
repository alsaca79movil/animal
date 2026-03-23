<?php
class Api_IndexController extends Zend_Controller_Action {

    private function _json($data, $code = 200) {
        $this->getResponse()->setHttpResponseCode($code);
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        $this->getResponse()->setHeader('Access-Control-Allow-Origin', '*');
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        echo json_encode($data);
    }

    public function cuidadoresAction() {
        try {
            $em = Zend_Registry::get('em');
            $cuidadores = $em->getRepository('app\models\Cuidador')
                             ->findBy(['activo' => 1], ['rating' => 'DESC'], 8);
    
            $data = [];
            foreach ($cuidadores as $c) {
                $usuario = $c->getUsuario(); // ya no necesitas find()
    
                // categorías via relación
                $categorias = $em->getRepository('app\models\cuidadorcategoria')
                                 ->findBy(['cuidador' => $c, 'activo' => 1]);
    
                $cats = [];
                foreach ($categorias as $cc) {
                    $cats[] = [
                        'nombre' => $cc->getCategoriamascota()->getNombre(),
                        'icono'  => $cc->getCategoriamascota()->getIcono(),
                    ];
                }
    
                $data[] = [
                    'id'          => $c->getId(),
                    'slug'        => $c->getSlug(),   // ← agrega esta línea
                    'nombre'      => $usuario->getNombre() . ' ' . $usuario->getApellido(),
                    'ciudad'      => $usuario->getCiudad(),
                    'foto'        => $usuario->getFoto(),
                    'descripcion' => $c->getDescripcion(),
                    'precionoche' => $c->getPrecionoche(),
                    'rating'      => $c->getRating(),
                    'totalresenas'=> $c->getTotalresenas(),
                    'superhost'   => $c->getSuperhost(),
                    'verificado'  => $c->getVerificado(),
                    'categorias'  => $cats,
                ];
            }
    
            $this->_json(['status' => 'ok', 'data' => $data]);
    
        } catch (\Exception $e) {
            $this->_json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    public function cuidadorAction() {
        try {
            $em  = Zend_Registry::get('em');
            $slug = $this->getRequest()->getParam('slug');
            $c    = $em->getRepository('app\models\Cuidador')->findOneBy(['slug' => $slug]);
                    
            if (!$c || !$c->getActivo()) {
                $this->_json(['status' => 'error', 'message' => 'No encontrado'], 404);
                return;
            }
    
            $usuario    = $c->getUsuario();
            $categorias = $em->getRepository('app\models\cuidadorcategoria')
            ->findBy(['cuidador' => $c, 'activo' => 1]);
    
            $cats = [];
            foreach ($categorias as $cc) {
                $cats[] = [
                    'nombre' => $cc->getCategoriamascota()->getNombre(),
                    'icono'  => $cc->getCategoriamascota()->getIcono(),
                ];
            }
    
            $this->_json(['status' => 'ok', 'data' => [
                'id'           => $c->getId(),
                'nombre'       => $usuario->getNombre() . ' ' . $usuario->getApellido(),
                'ciudad'       => $usuario->getCiudad(),
                'departamento' => $usuario->getDepartamento(),
                'foto'         => $usuario->getFoto(),
                'descripcion'  => $c->getDescripcion(),
                'precionoche'  => $c->getPrecionoche(),
                'preciodia'    => $c->getPreciodia(),
                'rating'       => $c->getRating(),
                'totalresenas' => $c->getTotalresenas(),
                'superhost'    => $c->getSuperhost(),
                'verificado'   => $c->getVerificado(),
                'tienepatio'   => $c->getTienepatio(),
                'capacidad'    => $c->getCapacidadmascotas(),
                'categorias'   => $cats,
            ]]);
    
        } catch (\Exception $e) {
            $this->_json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    public function categoriasAction() {
        $em   = Zend_Registry::get('em');
        $cats = $em->getRepository('app\models\categoriamascota')->findBy(['activo' => 1]);
        $data = [];
        foreach ($cats as $c) {
            $data[] = ['id' => $c->getId(), 'nombre' => $c->getNombre(), 'icono' => $c->getIcono()];
        }
        $this->_json(['status' => 'ok', 'data' => $data]);
    }
    
    public function registroAction() {
        try {
            $em   = Zend_Registry::get('em');
            $data = json_decode(file_get_contents('php://input'), true);
    
            if (!$data) {
                $this->_json(['status' => 'error', 'message' => 'Datos inválidos'], 400);
                return;
            }
    
            $data['categorias'] = $data['categorias'] ?? [];
            $rol = $data['rol'] ?? 'dueno';
    
            // Normalizar textos
            $emailNorm    = strtolower(trim($data['email'] ?? ''));
            $nombreNorm   = ucfirst(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', trim($data['nombre'] ?? ''))));
            $apellidoNorm = ucfirst(strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', trim($data['apellido'] ?? ''))));
            $ciudadNorm   = strtoupper(preg_replace('/[^a-zA-Z0-9\s]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', trim($data['ciudad'] ?? ''))));
    
            // Verificar email único
            $existe = $em->getRepository('app\models\Usuario')->findOneBy(['email' => $emailNorm]);
            if ($existe) {
                $this->_json(['status' => 'error', 'message' => 'El email ya está registrado'], 400);
                return;
            }
    
            // Validaciones básicas
            if (empty($emailNorm) || !filter_var($emailNorm, FILTER_VALIDATE_EMAIL)) {
                $this->_json(['status' => 'error', 'message' => 'Email inválido'], 400); return;
            }
            if (empty($data['password']) || strlen($data['password']) < 8) {
                $this->_json(['status' => 'error', 'message' => 'Contraseña mínimo 8 caracteres'], 400); return;
            }
            if (empty($nombreNorm) || empty($apellidoNorm) || empty($ciudadNorm)) {
                $this->_json(['status' => 'error', 'message' => 'Nombre, apellido y ciudad son requeridos'], 400); return;
            }
    
            // Crear usuario
            $esCuidador = in_array($rol, ['cuidador', 'ambos']);
    
            $usuario = new \app\models\Usuario();
            $usuario->setNombre($nombreNorm)
                    ->setApellido($apellidoNorm)
                    ->setEmail($emailNorm)
                    ->setPassword(password_hash($data['password'], PASSWORD_BCRYPT))
                    ->setTelefono(trim($data['telefono'] ?? '') ?: null)
                    ->setCiudad($ciudadNorm)
                    ->setEscuidador($esCuidador ? 1 : 0)
                    ->setTienemascota(in_array($rol, ['dueno', 'ambos']) ? 1 : 0)
                    ->setActivo(1)
                    ->setCreatedat(new \DateTime());
    
            $em->persist($usuario);
            $em->flush();
    
            // Si es cuidador — crear perfil y asignar categorías
            if ($esCuidador) {
                try {
                    $base = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT',
                        $nombreNorm . '-' . $apellidoNorm . '-' . $ciudadNorm));
                    $base = preg_replace('/[^a-z0-9-]/', '', str_replace(' ', '-', $base));
                    $slug = $base; $i = 1;
                    while ($em->getRepository('app\models\Cuidador')->findOneBy(['slug' => $slug])) {
                        $slug = $base . '-' . $i++;
                    }
    
                    $cuidador = new \app\models\Cuidador();
                    $cuidador->setUsuario($usuario)
                             ->setSlug($slug)
                             ->setDescripcion(trim($data['descripcion'] ?? '') ?: null)
                             ->setPrecionoche(!empty($data['precionoche']) ? (float)$data['precionoche'] : null)
                             ->setPreciodia(!empty($data['preciodia'])     ? (float)$data['preciodia']   : null)
                             ->setCapacidadmascotas(!empty($data['capacidad']) ? (int)$data['capacidad'] : null)
                             ->setTienepatio(!empty($data['tienepatio']) ? 1 : 0)
                             ->setActivo(1)
                             ->setCreatedat(new \DateTime());
    
                    $em->persist($cuidador);
                    $em->flush();
    
                    foreach ($data['categorias'] as $catId) {
                        $cat = $em->getRepository('app\models\categoriamascota')->find($catId);
                        if (!$cat) continue;
                        $cc = new \app\models\cuidadorcategoria();
                        $cc->setCuidador($cuidador)
                           ->setCategoriamascota($cat)
                           ->setActivo(1);
                        $em->persist($cc);
                    }
                    $em->flush();
    
                } catch (\Exception $e) {
                    $this->_json(['status' => 'error', 'message' => 'Error cuidador: ' . $e->getMessage()], 500);
                    return;
                }
            }
    
            $this->_json(['status' => 'ok', 'message' => 'Cuenta creada']);
    
        } catch (\Exception $e) {
            $this->_json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    public function loginAction() {
        try {
            $em   = Zend_Registry::get('em');
            $data = json_decode(file_get_contents('php://input'), true);
    
            if (!$data) {
                $this->_json(['status' => 'error', 'message' => 'Datos inválidos'], 400); return;
            }
    
            $email   = strtolower(trim($data['email'] ?? ''));
            $usuario = $em->getRepository('app\models\Usuario')
                          ->findOneBy(['email' => $email, 'activo' => 1]);
    
            if (!$usuario || !password_verify($data['password'] ?? '', $usuario->getPassword())) {
                $this->_json(['status' => 'error', 'message' => 'Email o contraseña incorrectos'], 401); return;
            }
    
            // Actualizar ultimo acceso
            $usuario->setUltimoacceso(new \DateTime());
            $em->flush();
    
            // Generar JWT
            $payload = [
                'iat'          => time(),
                'exp'          => time() + (60 * 60 * 24 * 7), // 7 días
                'usuario_id'   => $usuario->getId(),
                'nombre'       => $usuario->getNombre(),
                'email'        => $usuario->getEmail(),
                'escuidador'   => $usuario->getEscuidador(),
                'tienemascota' => $usuario->getTienemascota(),
            ];
    
            $token = \Firebase\JWT\JWT::encode($payload, 'ANIMALPET_SECRET_2025', 'HS256');
    
            $this->_json(['status' => 'ok', 'token' => $token, 'data' => [
                'nombre'     => $usuario->getNombre(),
                'escuidador' => $usuario->getEscuidador(),
            ]]);
    
        } catch (\Exception $e) {
            $this->_json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    public function mipanelAction() {
        $tokenData = $this->_getTokenData();
        if (!$tokenData) {
            $this->_json(['status' => 'error', 'message' => 'No autorizado'], 401);
            return;
        }
    
        try {
            $em      = Zend_Registry::get('em');
            $usuario = $em->getRepository('app\models\Usuario')->find($tokenData->usuario_id);
    
            $data = [
                'id'           => $usuario->getId(),
                'nombre'       => $usuario->getNombre(),
                'apellido'     => $usuario->getApellido(),
                'email'        => $usuario->getEmail(),
                'ciudad'       => $usuario->getCiudad(),
                'departamento' => $usuario->getDepartamento(),
                'telefono'     => $usuario->getTelefono(),
                'escuidador'   => $usuario->getEscuidador(),
                'tienemascota' => $usuario->getTienemascota(),
                'foto'         => $usuario->getFoto(),
            ];
    
            if ($usuario->getEscuidador()) {
                $cuidador = $em->getRepository('app\models\Cuidador')
                               ->findOneBy(['usuario' => $usuario]);
                if ($cuidador) {
                    // Categorías
                    $categorias = $em->getRepository('app\models\cuidadorcategoria')
                                     ->findBy(['cuidador' => $cuidador, 'activo' => 1]);
                    $cats = [];
                    foreach ($categorias as $cc) {
                        $cats[] = [
                            'id'     => $cc->getCategoriamascota()->getId(),
                            'nombre' => $cc->getCategoriamascota()->getNombre(),
                            'icono'  => $cc->getCategoriamascota()->getIcono(),
                        ];
                    }
    
                    $data['cuidador'] = [
                        'slug'              => $cuidador->getSlug(),
                        'descripcion'       => $cuidador->getDescripcion(),
                        'precionoche'       => $cuidador->getPrecionoche(),
                        'preciodia'         => $cuidador->getPreciodia(),
                        'capacidadmascotas' => $cuidador->getCapacidadmascotas(),
                        'tienepatio'        => $cuidador->getTienepatio(),
                        'direccion'         => $cuidador->getDireccion(),
                        'latitud'           => $cuidador->getLatitud(),
                        'longitud'          => $cuidador->getLongitud(),
                        'rating'            => $cuidador->getRating(),
                        'totalresenas'      => $cuidador->getTotalresenas(),
                        'superhost'         => $cuidador->getSuperhost(),
                        'verificado'        => $cuidador->getVerificado(),
                        'categorias'        => $cats,
                    ];
                }
            }
    
            $this->_json(['status' => 'ok', 'data' => $data]);
    
        } catch (\Exception $e) {
            $this->_json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    private function _getTokenData() {
        // Intenta de varias formas obtener el header
        $header = '';
        
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $header = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        } elseif (function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            $header  = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        }
    
        $token = str_replace('Bearer ', '', $header);
        if (!$token) return null;
    
        try {
            return \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key('ANIMALPET_SECRET_2025', 'HS256'));
        } catch (\Exception $e) {
            return null;
        }
    }
    
    public function actualizarperfilAction() {
        $tokenData = $this->_getTokenData();
        if (!$tokenData) { $this->_json(['status'=>'error','message'=>'No autorizado'],401); return; }
        try {
            $em   = Zend_Registry::get('em');
            $data = json_decode(file_get_contents('php://input'), true);
            $u    = $em->getRepository('app\models\Usuario')->find($tokenData->usuario_id);
    
            $u->setNombre(ucfirst(strtolower(trim($data['nombre'] ?? $u->getNombre()))))
              ->setApellido(ucfirst(strtolower(trim($data['apellido'] ?? $u->getApellido()))))
              ->setTelefono(trim($data['telefono'] ?? '') ?: null)
              ->setCiudad(strtoupper(trim($data['ciudad'] ?? $u->getCiudad())))
              ->setDepartamento(strtoupper(trim($data['departamento'] ?? '')) ?: null)
              ->setTienemascota(isset($data['tienemascota']) ? ($data['tienemascota'] ? 1 : 0) : $u->getTienemascota())
              ->setUpdatedat(new \DateTime());
    
            $em->flush();
            $this->_json(['status' => 'ok']);
    
        } catch(\Exception $e) {
            $this->_json(['status'=>'error','message'=>$e->getMessage()],500);
        }
    }
    
    public function actualizarcuidadorAction() {
        $tokenData = $this->_getTokenData();
        if (!$tokenData) { $this->_json(['status'=>'error','message'=>'No autorizado'],401); return; }
        try {
            $em   = Zend_Registry::get('em');
            $data = json_decode(file_get_contents('php://input'), true);
            $u    = $em->getRepository('app\models\Usuario')->find($tokenData->usuario_id);
            $c    = $em->getRepository('app\models\Cuidador')->findOneBy(['usuario' => $u]);
            if (!$c) { $this->_json(['status'=>'error','message'=>'Perfil no encontrado'],404); return; }
    
            $c->setDescripcion(trim($data['descripcion'] ?? '') ?: null)
              ->setPrecionoche(!empty($data['precionoche'])       ? (float)$data['precionoche']       : null)
              ->setPreciodia(!empty($data['preciodia'])           ? (float)$data['preciodia']         : null)
              ->setCapacidadmascotas(!empty($data['capacidadmascotas']) ? (int)$data['capacidadmascotas'] : null)
              ->setTienepatio(isset($data['tienepatio'])          ? ($data['tienepatio'] ? 1 : 0)     : null)
              ->setDireccion(trim($data['direccion'] ?? '')       ?: null)
              ->setLatitud(!empty($data['latitud'])               ? (float)$data['latitud']           : null)
              ->setLongitud(!empty($data['longitud'])             ? (float)$data['longitud']          : null)
              ->setUpdatedat(new \DateTime());
            $em->flush();
    
            // Actualizar categorías
            if (isset($data['categorias'])) {
                // Borrar las actuales
                $actuales = $em->getRepository('app\models\cuidadorcategoria')->findBy(['cuidador' => $c]);
                foreach ($actuales as $a) $em->remove($a);
                $em->flush();
    
                // Insertar las nuevas
                foreach ($data['categorias'] as $catId) {
                    $cat = $em->getRepository('app\models\categoriamascota')->find($catId);
                    if (!$cat) continue;
                    $cc = new \app\models\cuidadorcategoria();
                    $cc->setCuidador($c)->setCategoriamascota($cat)->setActivo(1);
                    $em->persist($cc);
                }
                $em->flush();
            }
    
            $this->_json(['status' => 'ok']);
    
        } catch(\Exception $e) {
            $this->_json(['status'=>'error','message'=>$e->getMessage()],500);
        }
    }
    
    public function cambiarpasswordAction() {
        $tokenData = $this->_getTokenData();
        if (!$tokenData) { $this->_json(['status'=>'error','message'=>'No autorizado'],401); return; }
        try {
            $em   = Zend_Registry::get('em');
            $data = json_decode(file_get_contents('php://input'), true);
            $u    = $em->getRepository('app\models\Usuario')->find($tokenData->usuario_id);
            if (!password_verify($data['password_actual'] ?? '', $u->getPassword())) {
                $this->_json(['status'=>'error','message'=>'Contraseña actual incorrecta'],400); return;
            }
            if (strlen($data['password_nueva'] ?? '') < 8) {
                $this->_json(['status'=>'error','message'=>'Mínimo 8 caracteres'],400); return;
            }
            $u->setPassword(password_hash($data['password_nueva'], PASSWORD_BCRYPT));
            $em->flush();
            $this->_json(['status'=>'ok']);
        } catch(\Exception $e) {
            $this->_json(['status'=>'error','message'=>$e->getMessage()],500);
        }
    }
    
    public function buscarcuidadoresAction() {
        try {
            $em      = Zend_Registry::get('em');
            $especie = $this->getRequest()->getParam('especie');
            $ciudad  = strtoupper(trim($this->getRequest()->getParam('ciudad') ?? ''));
    
            $qb = $em->createQueryBuilder();
            $qb->select('c')->from('app\models\Cuidador', 'c')
            ->join('c.usuario', 'u')
            ->where('c.activo = 1');
    
            if ($ciudad) {
                $qb->andWhere('u.ciudad = :ciudad')->setParameter('ciudad', $ciudad);
            }
    
            if ($especie) {
                $qb->join('app\models\cuidadorcategoria', 'cc', 'WITH', 'cc.cuidador = c')
                ->join('cc.categoriamascota', 'cat')
                ->andWhere('cat.id = :especie')->setParameter('especie', (int)$especie);
            }
    
            $qb->orderBy('c.rating', 'DESC')->setMaxResults(20);
            $cuidadores = $qb->getQuery()->getResult();
    
            $data = [];
            foreach ($cuidadores as $c) {
                $usuario    = $c->getUsuario();
                $categorias = $em->getRepository('app\models\cuidadorcategoria')
                ->findBy(['cuidador' => $c, 'activo' => 1]);
                $cats = [];
                foreach ($categorias as $cc) {
                    $cats[] = ['nombre' => $cc->getCategoriamascota()->getNombre(), 'icono' => $cc->getCategoriamascota()->getIcono()];
                }
                $data[] = [
                    'slug'       => $c->getSlug(),
                    'nombre'     => $usuario->getNombre() . ' ' . $usuario->getApellido(),
                    'ciudad'     => $usuario->getCiudad(),
                    'foto'       => $usuario->getFoto(),
                    'precionoche'=> $c->getPrecionoche(),
                    'rating'     => $c->getRating(),
                    'superhost'  => $c->getSuperhost(),
                    'categorias' => $cats,
                ];
            }
    
            $this->_json(['status' => 'ok', 'data' => $data]);
    
        } catch(\Exception $e) {
            $this->_json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    public function guardarmascotaAction() {
        $tokenData = $this->_getTokenData();
        if (!$tokenData) { $this->_json(['status'=>'error','message'=>'No autorizado'],401); return; }
    
        try {
            $em      = Zend_Registry::get('em');
            $usuario = $em->getRepository('app\models\Usuario')->find($tokenData->usuario_id);
            $baseUrl = Zend_Controller_Front::getInstance()->getRequest()->getBaseUrl();
    
            $nombre      = trim($_POST['nombre'] ?? '');
            $raza        = trim($_POST['raza'] ?? '');
            $edad        = $_POST['edad'] ?? null;
            $peso        = $_POST['peso'] ?? null;
            $descripcion = trim($_POST['descripcion'] ?? '');
            $categoriaId = $_POST['categoria_id'] ?? null;
    
            if (!$nombre) { $this->_json(['status'=>'error','message'=>'Nombre requerido'],400); return; }
    
            $categoria = $categoriaId ? $em->getRepository('app\models\categoriamascota')->find($categoriaId) : null;
    
            $mascota = new \app\models\Mascota();
            $mascota->setNombre($nombre)
                    ->setRaza($raza ?: null)
                    ->setEdad($edad ? (float)$edad : null)
                    ->setPeso($peso ? (float)$peso : null)
                    ->setDescripcion($descripcion ?: null)
                    ->setCategoria($categoria)
                    ->setUsuario($usuario)
                    ->setActivo(1)
                    ->setCreatedat(new \DateTime());
            $em->persist($mascota);
            $em->flush();
    
            $uploadDir = APPLICATION_PATH . '/../public/uploads/mascotas/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    
            $fotos = $_FILES['fotos'] ?? null;
            if ($fotos && is_array($fotos['tmp_name'])) {
                $contador = 0;
                foreach ($fotos['tmp_name'] as $i => $tmp) {
                    if ($contador >= 5) break;
                    if ($fotos['error'][$i] !== UPLOAD_ERR_OK) continue;
    
                    $info = getimagesize($tmp);
                    if (!$info) continue;
    
                    $mime = $info['mime'];
                    $src  = null;
                    if ($mime === 'image/jpeg')     $src = imagecreatefromjpeg($tmp);
                    elseif ($mime === 'image/png')  $src = imagecreatefrompng($tmp);
                    elseif ($mime === 'image/webp') $src = imagecreatefromwebp($tmp);
                    if (!$src) continue;
    
                    $w = imagesx($src); $h = imagesy($src); $maxW = 800;
                    if ($w > $maxW) {
                        $newH = (int)(($h * $maxW) / $w);
                        $dst  = imagecreatetruecolor($maxW, $newH);
                        imagecopyresampled($dst, $src, 0, 0, 0, 0, $maxW, $newH, $w, $h);
                        imagedestroy($src);
                        $src = $dst;
                    }
    
                    $filename = 'mascota_' . $mascota->getId() . '_' . time() . '_' . $i . '.jpg';
                    imagejpeg($src, $uploadDir . $filename, 80);
                    imagedestroy($src);
    
                    $foto = new \app\models\MascotaFoto();
                    $foto->setMascota($mascota)
                         ->setUrl($baseUrl . '/uploads/mascotas/' . $filename)
                         ->setOrden($contador)
                         ->setCreatedat(new \DateTime());
                    $em->persist($foto);
                    $contador++;
                }
                $em->flush();
            }
    
            $this->_json(['status' => 'ok', 'id' => $mascota->getId()]);
    
        } catch(\Exception $e) {
            $this->_json(['status'=>'error','message'=>$e->getMessage()],500);
        }
    }
    
    public function mismascotasAction() {
        $tokenData = $this->_getTokenData();
        if (!$tokenData) { $this->_json(['status'=>'error','message'=>'No autorizado'],401); return; }
        try {
            $em       = Zend_Registry::get('em');
            $usuario  = $em->getRepository('app\models\Usuario')->find($tokenData->usuario_id);
            $mascotas = $em->getRepository('app\models\Mascota')->findBy(['usuario'=>$usuario,'activo'=>1]);
            $data = [];
            foreach ($mascotas as $m) {
                $fotos = $em->getRepository('app\models\MascotaFoto')->findBy(['mascota'=>$m],['orden'=>'ASC']);
                $data[] = [
                    'id'             => $m->getId(),
                    'nombre'         => $m->getNombre(),
                    'raza'           => $m->getRaza(),
                    'edad'           => $m->getEdad(),
                    'peso'           => $m->getPeso(),
                    'descripcion'    => $m->getDescripcion(),
                    'categoria'      => $m->getCategoria()?->getNombre(),
                    'categoria_icono'=> $m->getCategoria()?->getIcono(),
                    'fotos'          => array_map(fn($f) => $f->getUrl(), $fotos),
                    'categoria_id'   => $m->getCategoria()?->getId(),
                ];
            }
            $this->_json(['status'=>'ok','data'=>$data]);
        } catch(\Exception $e) {
            $this->_json(['status'=>'error','message'=>$e->getMessage()],500);
        }
    }
    
    public function editarmascotaAction() {
        $tokenData = $this->_getTokenData();
        if (!$tokenData) { $this->_json(['status'=>'error','message'=>'No autorizado'],401); return; }
        try {
            $em   = Zend_Registry::get('em');
            $data = json_decode(file_get_contents('php://input'), true);
            $m    = $em->getRepository('app\models\Mascota')->find($data['id']);
            if (!$m || $m->getUsuario()->getId() != $tokenData->usuario_id) {
                $this->_json(['status'=>'error','message'=>'No autorizado'],403); return;
            }
            $cat = !empty($data['categoria_id']) ? $em->getRepository('app\models\categoriamascota')->find($data['categoria_id']) : null;
            $m->setNombre(trim($data['nombre'] ?? $m->getNombre()))
            ->setRaza(trim($data['raza'] ?? '') ?: null)
            ->setEdad(!empty($data['edad']) ? (float)$data['edad'] : null)
            ->setPeso(!empty($data['peso']) ? (float)$data['peso'] : null)
            ->setDescripcion(trim($data['descripcion'] ?? '') ?: null)
            ->setCategoria($cat);
            $em->flush();
            $this->_json(['status'=>'ok']);
        } catch(\Exception $e) {
            $this->_json(['status'=>'error','message'=>$e->getMessage()],500);
        }
    }
    
    public function eliminarmascotaAction() {
        $tokenData = $this->_getTokenData();
        if (!$tokenData) { $this->_json(['status'=>'error','message'=>'No autorizado'],401); return; }
        try {
            $em = Zend_Registry::get('em');
            $data = json_decode(file_get_contents('php://input'), true);
            $m    = $em->getRepository('app\models\Mascota')->find($data['id']);
            if (!$m || $m->getUsuario()->getId() != $tokenData->usuario_id) {
                $this->_json(['status'=>'error','message'=>'No autorizado'],403); return;
            }
            $m->setActivo(0);
            $em->flush();
            $this->_json(['status'=>'ok']);
        } catch(\Exception $e) {
            $this->_json(['status'=>'error','message'=>$e->getMessage()],500);
        }
    }
    
    
    
    
}