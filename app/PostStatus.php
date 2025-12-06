<?php

namespace App;

enum PostStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
}
