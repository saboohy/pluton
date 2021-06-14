<?php

/**
 *  Model siniflərini işə salacaq.
 * 
 *  @return mixed
 */
if(!function_exists('model')) {

    function model() {

        # Gələn arqumentləri alsın!
        $getModel = func_get_args();

        # Sadəcə 1 model daxili olunarsa.
        if(count($getModel) === 1) {

            # Təyin olunan model.
            $singleModel = ucfirst($getModel[0]);

            # Model faylın tam istiqaməti.
            $singleModelFile = MODEL . $singleModel . '.php';

            # Model sinfinin tam adı.
            $singleModelClass = '\App\Models\\' . $singleModel;

            # Model faylın mövcudluq kontrolu.
            if(file_exists($singleModelFile)) {

                # Model sinfinin mövcudluq kontrolu.
                if(class_exists($singleModelClass)) {

                    # Sinfi döndürsün.
                    return new $singleModelClass;
                } else {

                    # Xəta! Sinif yoxdur.
                    warning([
                        'title' => 'Model xətası!',
                        'hint' => $singleModelClass . ' sinfi mövcud deyil.'
                    ]);
                };
            } else {
                
                # Xəta! Fayl yoxdur.
                warning([
                    'title' => 'Model xətası!',
                    'hint' => $singleModel . '.php faylı mövcud deyil.'
                ]);
            };
        } else {

            /**
             *  Model siniflərini tutacaq.
             * 
             *  @var array
             */
            $models = [];

            # Qəbul olunan arqumentləri dövrdə açmaq.
            foreach ($getModel as $model) {
                
                # Alınan dəyərin baş hərfini böyütmək.
                $model = ucfirst($model);

                # Model faylın tam istiqaməti.
                $modelFile = MODEL . $model . '.php';

                # Model sinfinin tam adı.
                $modelClass = '\App\Models\\' . $model;

                # Model faylın mövcudluq kontrolu.
                if(file_exists($modelFile)) {

                    # Model sinfin mpvcudluq kontrolu.
                    if(class_exists($modelClass)) {

                        # Model sinifləri massivə yerləşsin!
                        $models[] = new $modelClass;
                    }else {

                        # Xəta! Sinif yoxdur.
                        warning([
                            'title' => 'Model xətası!',
                            'hint' => $singleModelClass . ' sinfi mövcud deyil.'
                        ]);
                    }
                }else {

                    # Xəta! Fayl yoxdur.
                    warning([
                        'title' => 'Model xətası!',
                        'hint' => $singleModel . '.php faylı mövcud deyil.'
                    ]);
                }
            }

            return $models;
        }
    }
}