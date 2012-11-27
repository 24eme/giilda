diff --git a/lib/DSLatex.class.php b/lib/DSLatex.class.php
index 6c1a8fa..39a10a2 100644
--- a/lib/DSLatex.class.php
+++ b/lib/DSLatex.class.php
@@ -54,6 +54,7 @@ class DSLatex {
 
   public function generatePDF() {
     $cmdCompileLatex = '/usr/bin/pdflatex -output-directory="'.$this->getTEXWorkingDir().'" -synctex=1 -interaction=nonstopmode "'.$this->getLatexFile().'" 2>&1';
+    $output = array();
     exec($cmdCompileLatex, $output, $ret);
     $output = implode(' ', $output);
     if (!preg_match('/Transcript written/', $output)) {
@@ -64,8 +65,10 @@ class DSLatex {
       $grep = preg_grep('/^!/', file_get_contents($log));
       array_unshift($grep, "/!\ Latex error\n");
       array_unshift($grep, "Latex log $log:\n");
-      if ($grep)
-	      throw new sfException(implode(' ', $grep));
+      if ($grep){
+            echo 'AIE => '.$log;
+              throw new sfException(implode(' ', $grep));
+      }
     }
     return $this->getLatexFileNameWithoutExtention().'.pdf';
   }
diff --git a/modules/ds/templates/_generateTex.php b/modules/ds/templates/_generateTex.php
index 742eb7d..7cd65bf 100644
--- a/modules/ds/templates/_generateTex.php
+++ b/modules/ds/templates/_generateTex.php
@@ -37,8 +37,8 @@
 
 \def\DSClientNUM{<?php echo $ds->identifiant; ?>}
 \def\DSClientCVI{<?php echo $ds->declarant->cvi; ?>}
-\def\DSClientNom{<?php echo $ds->declarant->raison_sociale; ?>}
-\def\DSClientAdresse{<?php echo ($ds->declarant->adresse == "")? "ADRESSE" : $ds->declarant->adresse; ?>}
+\def\DSClientNom{<?php echo ($ds->declarant->raison_sociale)? $ds->declarant->raison_sociale : $ds->declarant->nom ; ?>}
+\def\DSClientAdresse{<?php echo ($ds->declarant->adresse)? "~" : $ds->declarant->adresse; ?>}
 \def\DSClientCP{<?php echo $ds->declarant->code_postal; ?>}
 \def\DSClientVille{<?php echo $ds->declarant->commune; ?>}
 
diff --git a/modules/ds/templates/_historiqueDsGeneration.php b/modules/ds/templates/_historiqueDsGeneration.php
index df08898..71ebc5f 100644
--- a/modules/ds/templates/_historiqueDsGeneration.php
+++ b/modules/ds/templates/_historiqueDsGeneration.php
@@ -33,7 +33,8 @@ use_helper('Float');
                         <td><?php echo link_to($generation->key[GenerationClient::HISTORY_KEYS_TYPE_DATE_EMISSION], 'generation_view', array('type_document' => GenerationClient::TYPE_DOCUMENT_DS, 'date_emission' => $generation->key[GenerationClient::HISTORY_KEYS_TYPE_DATE_EMISSION])); ?></td>
                         <td><?php
                             echo $generation->value[GenerationClient::HISTORY_VALUES_NBDOC];
-                    ?></td>
+                        ?>
+                        </td>
                     </tr>
     <?php endforeach; ?>
             </tbody>
@@ -44,5 +45,5 @@ endif;
 </fieldset>
 <div class="historique_generation_ds">
     <span>Consulter l'historique de générations DS</span>
-        <a href="<?php echo url_for('generation_list',array('type_document' => 'DS')); ?>" id="historique_generation" class="btn_majeur">Consulter</a>
+    <a href="<?php echo url_for('generation_list',array('type_document' => GenerationClient::TYPE_DOCUMENT_DS)); ?>" id="historique_generation" class="btn_majeur">Consulter</a>
 </div>
