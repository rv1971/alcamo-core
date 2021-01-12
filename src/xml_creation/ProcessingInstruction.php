<?php

/// XML processing instruction.
class ProcessingInstruction implements NodeInterface {
  protected $target_;   ///< PI target.

  function __construct( $target, $content ) {
    if ( strpos( $content, '?>' ) !== false ) {
      throw new SyntaxError(
        $content, strpos( $content, '?>' ), '; "?>" in XML PI' );
    }

    $this->target_ = $target;

    parent::__construct( $content );
  }

  public function getTarget() {
    return $this->target_;
  }

  function __toString() {
    return "<?{$this->target_} {$this->content_}?>";
  }
}
